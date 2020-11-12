<?php

namespace UserBundle\Utils;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class Canonicalizer implements CanonicalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function canonicalize(string $string)
    {
        if (null === $string) {
            return null;
        }

        $encoding = mb_detect_encoding($string);
        $result = $encoding
            ? mb_convert_case($string, MB_CASE_LOWER, $encoding)
            : mb_convert_case($string, MB_CASE_LOWER);

        return $result;
    }
}
