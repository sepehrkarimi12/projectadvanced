<?php
namespace app\modules\secretariat_v2\Interfaces;

/**
 * Chain of responsibilities handler interface
 * @author Noei
 */
interface Handler
{
    public function handleRequest($request);
    public function setSuccessor($nextService);
}