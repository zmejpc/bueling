<?php

namespace ComponentBundle\Entity\MailSetting;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait MailSettingTranslationTrait
{
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
     * @return null|string
     */
    public function getSenderName(): ?string
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName(string $senderName): void
    {
        $this->senderName = $senderName;
    }

    /**
     * @return null|string
     */
    public function getManagerSubject(): ?string
    {
        return $this->managerSubject;
    }

    /**
     * @param string $managerSubject
     */
    public function setManagerSubject(string $managerSubject): void
    {
        $this->managerSubject = $managerSubject;
    }

    /**
     * @return null|string
     */
    public function getSuccessFlashMessage(): ?string
    {
        return $this->successFlashMessage;
    }

    /**
     * @param string $successFlashMessage
     */
    public function setSuccessFlashMessage(string $successFlashMessage): void
    {
        $this->successFlashMessage = $successFlashMessage;
    }

    /**
     * @return null|string
     */
    public function getSuccessFlashTitle(): ?string
    {
        return $this->successFlashTitle;
    }

    /**
     * @param string $successFlashTitle
     */
    public function setSuccessFlashTitle(string $successFlashTitle): void
    {
        $this->successFlashTitle = $successFlashTitle;
    }
}
