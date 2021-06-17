<?php
/**
 * This class has the definition of the API used for the communication.
 * @copyright 2021 Loxopay
 * @since     v1.0
 */
namespace Loxopay;

/**
 * This object handles the communication with the API server
 * @package Loxopay
 */
class Communicator
{
    const VERSION = 'v1';
    const API_URL_FORMAT = 'https://api.%s/%s/%s/%d/%s';
    const API_URL_BASE_DOMAIN = 'loxopay.ch';
    const DEFAULT_COMMUNICATION_HANDLER = '\Loxopay\CommunicationAdapter\CurlCommunication';

    /**
     * @var array A set of methods which can be used to communicate with the API server.
     */
    protected static $methods = array(
        'create'  => 'POST',
        'charge'  => 'POST',
        'refund'  => 'POST',
        'capture' => 'POST',
        'cancel'  => 'DELETE',
        'delete'  => 'DELETE',
        'update'  => 'PUT',
        'getAll'  => 'GET',
        'getOne'  => 'GET',
    );
    /**
     * @var string The Loxopay instance name.
     */
    protected $instance;
    /**
     * @var string The API secret which is used to generate a signature.
     */
    protected $apiSecret;
    /**
     * @var string The base domain of the API URL.
     */
    protected $apiBaseDomain;
    /**
     * @var string The communication handler which handles the HTTP requests. Default cURL Communication handler
     */
    protected $communicationHandler;

    /**
     * Generates a communicator object with a communication handler like cURL.
     *
     * @param string $instance             The instance name, needed for the generation of the API url.
     * @param string $apiSecret            The API secret which is the key to hash all the parameters passed to the API server.
     * @param string $communicationHandler The preferred communication handler. Default is cURL.
     * @param string $apiBaseDomain        The base domain of the API URL.
     *
     * @throws LoxopayException
     */
    public function __construct($instance, $apiSecret, $communicationHandler, $apiBaseDomain)
    {
        $this->instance = $instance;
        $this->apiSecret = $apiSecret;
        $this->apiBaseDomain = $apiBaseDomain;

        if (!class_exists($communicationHandler)) {
            throw new LoxopayException('Communication handler class ' . $communicationHandler . ' not found');
        }
        $this->communicationHandler = new $communicationHandler();
    }

    /**
     * Gets the version of the API used.
     *
     * @return string The version of the API
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Perform a simple API request by method name and Request model.
     *
     * @param string                       $method The name of the API method to call
     * @param \Loxopay\Models\Base $model  The model which has the same functionality like a filter.
     *
     * @return \Loxopay\Models\Base[]|\Loxopay\Models\Base An array of models or just one model which
     *                                                                       is the result of the API call
     * @throws \Loxopay\LoxopayException An error occurred during the Loxopay Request
     */
    public function performApiRequest($method, \Loxopay\Models\Base $model)
    {
        $params = $model->toArray($method);
        $paramsWithoutFiles = $params;
        unset($paramsWithoutFiles['headerImage'], $paramsWithoutFiles['backgroundImage'], $paramsWithoutFiles['headerBackgroundImage'], $paramsWithoutFiles['emailHeaderImage'], $paramsWithoutFiles['VPOSBackgroundImage']);
        $params['ApiSignature'] =
            base64_encode(hash_hmac('sha256', http_build_query($paramsWithoutFiles, null, '&'), $this->apiSecret, true));
        $params['instance'] = $this->instance;

        $id = isset($params['id']) ? $params['id'] : 0;
        $act = in_array($method, ['refund', 'capture']) ? $method : '';
        $apiUrl = sprintf(self::API_URL_FORMAT, $this->apiBaseDomain, self::VERSION, $params['model'], $id, $act);

        $httpMethod = $this->getHttpMethod($method) === 'PUT' && $params['model'] === 'Design'
            ? 'POST'
            : $this->getHttpMethod($method);
        $response = $this->communicationHandler->requestApi(
            $apiUrl,
            $params,
            $httpMethod
        );

        $convertedResponse = array();
        if (!isset($response['body']['data']) || !is_array($response['body']['data'])) {
            if (!isset($response['body']['message'])) {
                throw new \Loxopay\LoxopayException('Loxopay PHP: Configuration is wrong! Check instance name and API secret', $response['info']['http_code']);
            }
            throw new \Loxopay\LoxopayException($response['body']['message'], $response['info']['http_code']);
        }

        foreach ($response['body']['data'] as $object) {
            $responseModel = $model->getResponseModel();
            $convertedResponse[] = $responseModel->fromArray($object);
        }
        if ($method !== 'getAll') {
            $convertedResponse = current($convertedResponse);
        }
        return $convertedResponse;
    }

    /**
     * Gets the HTTP method to use for a specific API method
     *
     * @param string $method The API method to check for
     *
     * @return string The HTTP method to use for the queried API method
     * @throws \Loxopay\LoxopayException The method is not implemented yet.
     */
    protected function getHttpMethod($method)
    {
        if (!$this->methodAvailable($method)) {
            throw new \Loxopay\LoxopayException('Method ' . $method . ' not implemented');
        }
        return self::$methods[$method];
    }

    /**
     * Checks whether a method is available and activated in methods array.
     *
     * @param string $method The method name to check
     *
     * @return bool True if the method exists, False if not
     */
    public function methodAvailable($method)
    {
        return array_key_exists($method, self::$methods);
    }
}
