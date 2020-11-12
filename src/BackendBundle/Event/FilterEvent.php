<?php

namespace BackendBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class FilterEvent extends Event
{
    private $request;

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }
}