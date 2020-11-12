<?php

namespace ContactBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CallbackStatusInterface
{
    /* YES / NO */
    public const YES = 1;
    public const NO = 0;

    public static function yesOrNo();

    public static function yesOrNoForm();

    /**
     * @return int|null
     */
    public function getId(): ?int;

    public function __toString();

    public function getPosition(): ?int;

    public function setPosition(int $position): void;

    public function getSystemName(): ?string;

    public function setSystemName(string $systemName): void;
}