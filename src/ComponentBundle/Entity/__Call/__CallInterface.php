<?php

namespace ComponentBundle\Entity\__Call;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface __CallInterface
{
    /**
     * @param $method
     * @param $arguments
     * @return mixed|null
     */
    public function __call($method, $arguments);
}
