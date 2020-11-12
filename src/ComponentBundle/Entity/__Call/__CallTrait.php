<?php

namespace ComponentBundle\Entity\__Call;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait __CallTrait
{
    /**
     * @param $method
     * @param $arguments
     * @return mixed|null
     */
    public function __call($method, $arguments)
    {
        if ($method == '_action') {
            return null;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }
}
