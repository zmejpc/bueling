<?php

namespace ComponentBundle\Entity\MailSetting;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait MailSettingTranslationExtendedTrait
{
    use MailSettingTranslationTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="message_subject",  type="string", length=255, nullable=false)
     */
    private $messageSubject;
    
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="message_body", type="text", nullable=false)
     */
    private $messageBody;

    /**
     * @return string
     */
    public function getMessageBody(): ?string
    {
        return $this->messageBody;
    }

    /**
     * @param string $messageBody
     */
    public function setMessageBody(string $messageBody): void
    {
        $this->messageBody = $messageBody;
    }

    /**
     * @return string
     */
    public function getMessageSubject(): ?string
    {
        return $this->messageSubject;
    }

    /**
     * @param string $messageSubject
     */
    public function setMessageSubject(string $messageSubject): void
    {
        $this->messageSubject = $messageSubject;
    }
}
