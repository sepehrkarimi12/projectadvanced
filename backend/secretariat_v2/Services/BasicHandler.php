<?php
namespace app\modules\secretariat_v2\Services;
use app\modules\secretariat_v2\Interfaces\Handler;

/**
 * All requests to chain of responsibilities must be passed via this class
 * @author Noei
 */
abstract class BasicHandler
{
    /**
     * @var Handler
     */
    protected $successor = null;

    /**
     * Sets a successor handler.
     * @param Handler $handler
     * @return object
     */
    public function setSuccessor($handler)
    {
        $this->successor = $handler;
        return $handler;
    }

    /**
     * Handles the request and/or redirect the request
     * to the successor.
     * @param mixed $request
     * @return mixed
     */
    abstract public function handleRequest($request);

}