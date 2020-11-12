<?php

namespace ComponentBundle\Twig\Extension;

use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

/**
 * @author Design studio origami <https://origami.ua>
 */
class HelpersExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('preg_replace', [$this, 'pregReplace']),
            new TwigFilter('reduce', [$this, 'reduce']),
        ];
    }

    public function pregReplace($subject, $pattern, $replacement, int $limit = -1, int &$count = 0)
    {
        return preg_replace($pattern, $replacement, $subject, $limit, $count);
    }

    public function reduce(array $array, callable $callback, mixed $initial = NULL)
    {
        return array_reduce($array, $callback, $initial);
    }
}
