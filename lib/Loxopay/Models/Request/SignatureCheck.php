<?php
/**
 * The signatureCheck request model
 * @copyright 2021 Loxopay
 * @since     v1.0
 */
namespace Loxopay\Models\Request;

/**
 * Class SignatureCheck
 * @package Loxopay\Models\Request
 */
class SignatureCheck extends \Loxopay\Models\Base
{
    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Loxopay\Models\Response\SignatureCheck();
    }
}
