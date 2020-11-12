<?php

namespace ComponentBundle\Entity\YesOrNo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait YesOrNoTrait
{
    /**
     * @return array
     */
    public static function yesOrNo(): array
    {
        return [
            self::YES => "form.yes",
            self::NO => "form.no"
        ];
    }

    /**
     * @return array
     */
    public static function yesOrNoForm(): array
    {
        return [
            "form.yes" => self::YES,
            "form.no" => self::NO,
        ];
    }
}
