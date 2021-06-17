<?php
/**
 * The Page response model
 * @copyright 2021 Loxopay
 * @since     v1.0
 */
namespace Loxopay\Models\Response;

/**
 * Class Page
 * @package Loxopay\Models\Response
 */
class Page extends \Loxopay\Models\Request\Page
{
    protected $createdAt = 0;

    /**
     * @return int
     */
    public function getCreatedDate()
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedDate($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }
}
