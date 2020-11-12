<?php

namespace ComponentBundle\Twig\Extension;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

/**
 * @author Design studio origami <https://origami.ua>
 */
class JsonDecodeExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('json_decode', [$this, 'jsonDecode'])
        ];
    }

    /**
     * @param $str
     * @return mixed
     */
    public function jsonDecode($str)
    {
        return json_decode($str, true);
    }
}