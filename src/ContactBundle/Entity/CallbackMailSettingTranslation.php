<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CallbackMailSettingTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_mail_setting_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class CallbackMailSettingTranslation
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="sender_name",  type="string", length=255, nullable=false)
     */
    private $senderName;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="manager_subject",  type="string", length=255, nullable=false)
     */
    private $managerSubject;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="success_flash_title", type="string", length=255, nullable=true)
     */
    private $successFlashTitle;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="success_flash_message",  type="text", nullable=true)
     */
    private $successFlashMessage;

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getManagerSubject()
    {
        return $this->managerSubject;
    }

    /**
     * @param string $managerSubject
     */
    public function setManagerSubject($managerSubject)
    {
        $this->managerSubject = $managerSubject;
    }

    /**
     * @return string
     */
    public function getSuccessFlashMessage()
    {
        return $this->successFlashMessage;
    }

    /**
     * @param string $successFlashMessage
     */
    public function setSuccessFlashMessage($successFlashMessage)
    {
        $this->successFlashMessage = $successFlashMessage;
    }

    /**
     * @return string
     */
    public function getSuccessFlashTitle(): ?string
    {
        return $this->successFlashTitle;
    }

    /**
     * @param string $successFlashTitle
     */
    public function setSuccessFlashTitle(?string $successFlashTitle): void
    {
        $this->successFlashTitle = $successFlashTitle;
    }
}