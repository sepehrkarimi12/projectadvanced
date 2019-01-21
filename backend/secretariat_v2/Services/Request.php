<?php
namespace app\modules\secretariat_v2\Services;

/**
 * All requests to chain of responsibilities must be passed via this class
 * @author Noei
 */
class Request
{

    private $value;

    public function __construct($service)
    {
        $this->value = $service;
    }

    public function getService()
    {
        return $this->value;

    }

}