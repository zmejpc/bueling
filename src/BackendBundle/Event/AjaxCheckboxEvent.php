<?php

namespace BackendBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AjaxCheckboxEvent extends Event
{
    private $entity;

    private $method;

    private $methodArgument;

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethodArgument($methodArgument)
    {
        $this->methodArgument = $methodArgument;
    }

    public function getMethodArgument()
    {
        return $this->methodArgument;
    }
}