<?php

/**
 * Transaction request model
 *
 * @copyright   Loxopay
 */
namespace Loxopay\Models\Request;

/**
 * Transaction class
 *
 * @package Loxopay\Models\Request
 */
class Transaction extends \Loxopay\Models\Base
{
    /** @var int $amount */
    protected $amount;
    /** @var string $purpose */
    protected $purpose;
    /** @var string $referenceId */
    protected $referenceId;
    protected $filterDatetimeUtcGreaterThan;
    protected $filterDatetimeUtcLessThan;
    protected $offset;
    protected $limit;

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * @return string
     */
    public function getReferenceId()
    {
        return $this->referenceId;
    }

    /**
     * @param string $referenceId
     */
    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
    }

    /**
     * @return \DateTime
     */
    public function getFilterDatetimeUtcGreaterThan()
    {
        return $this->filterDatetimeUtcGreaterThan;
    }

    /**
     * @param \DateTime $filterDatetimeUtcGreaterThan
     */
    public function setFilterDatetimeUtcGreaterThan(\DateTime $filterDatetimeUtcGreaterThan): void
    {
        $this->filterDatetimeUtcGreaterThan = $filterDatetimeUtcGreaterThan->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    /**
     * @return \DateTime
     */
    public function getFilterDatetimeUtcLessThan()
    {
        return $this->filterDatetimeUtcLessThan;
    }

    /**
     * @param \DateTime $filterDatetimeUtcLessThan
     */
    public function setFilterDatetimeUtcLessThan(\DateTime $filterDatetimeUtcLessThan): void
    {
        $this->filterDatetimeUtcLessThan = $filterDatetimeUtcLessThan->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d H:i:s');
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Loxopay\Models\Response\Transaction();
    }
}
