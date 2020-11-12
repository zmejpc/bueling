<?php

namespace ComponentBundle\Twig\Extension;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

/**
 * @author Design studio origami <https://origami.ua>
 */
class JsonDetectExtension extends AbstractExtension
{
    /**
     * @return array|TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('json_detect', [$this, 'jsonDetect'])
        ];
    }

    /**
     * @param $str
     * @return bool
     */
    public function jsonDetect($str)
    {
        $result = json_decode($str);
        if (json_last_error() === JSON_ERROR_NONE) {
            return true;
        }
        return false;
    }
}