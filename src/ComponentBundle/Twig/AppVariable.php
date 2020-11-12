<?php

namespace ComponentBundle\Twig;

use Symfony\Bridge\Twig\AppVariable as OriginalAppVariable;

/**
 * @author Design studio origami <https://origami.ua>
 */
class AppVariable extends OriginalAppVariable
{
    public function getMasterRequest()
    {
        if (null === $this->requestStack) {
            throw new \RuntimeException('The "app.masterRequest" variable is not available.');
        }

        return $this->requestStack->getMasterRequest();
    }
}
