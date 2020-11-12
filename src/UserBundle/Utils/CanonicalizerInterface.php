<?php

namespace UserBundle\Utils;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CanonicalizerInterface
{
    /**
     * @param string $string
     * @return mixed
     */
    public function canonicalize(string $string);
}
