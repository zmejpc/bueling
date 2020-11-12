<?php

namespace ContactBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CallbackManagerInterface
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

    public function getEmail(): ?string;

    public function setEmail(string $email): void;

    public function getName(): ?string;

    public function setName(string $name): void;

    public function getIsSendForEmail(): bool;

    public function setIsSendForEmail(bool $isSendForEmail): void;
}