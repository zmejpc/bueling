<?php

namespace ComponentBundle\Entity\MailSetting;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface MailSettingTranslationInterface
{
    /**
     * @return null|string
     */
    public function getSenderName(): ?string;

    /**
     * @param string $senderName
     */
    public function setSenderName(string $senderName): void;

    /**
     * @return null|string
     */
    public function getManagerSubject(): ?string;

    /**
     * @param string $managerSubject
     */
    public function setManagerSubject(string $managerSubject): void;

    /**
     * @return null|string
     */
    public function getSuccessFlashTitle(): ?string;

    /**
     * @param string $successFlashTitle
     */
    public function setSuccessFlashTitle(string $successFlashTitle): void;

    /**
     * @return null|string
     */
    public function getSuccessFlashMessage(): ?string;

    /**
     * @param string $successFlashMessage
     */
    public function setSuccessFlashMessage(string $successFlashMessage): void;
}
