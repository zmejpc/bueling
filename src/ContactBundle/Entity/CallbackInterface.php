<?php

namespace ContactBundle\Entity;

use UserBundle\Entity\User;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CallbackInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): void;

    public function getPhone(): ?string;

    public function setPhone(string $phone): void;

    public function getStatus(): ?CallbackStatus;

    public function setStatus(CallbackStatus $status): void;

    public function setUpdatedBy(User $updatedBy = null);

    public function getUpdatedBy();

    public function setCreatedBy(User $createdBy = null);

    public function getCreatedBy();

    public function getMessage(): ?string;

    public function setMessage(string $message): void;
}