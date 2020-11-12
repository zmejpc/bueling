<?php

namespace ComponentBundle\Entity\MailSetting;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait MailSettingTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="smtp_host", type="string", length=255, nullable=false)
     */
    private $smtpHost;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="smtp_username", type="string", length=255, nullable=false)
     */
    private $smtpUsername;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="smtp_password", type="string", length=255, nullable=false)
     */
    private $smtpPassword;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="smtp_port", type="string", length=255, nullable=false)
     */
    private $smtpPort = 25;

    /**
     * @return string
     */
    public function getSmtpHost(): ?string
    {
        return $this->smtpHost;
    }

    /**
     * @param string $smtpHost
     */
    public function setSmtpHost(string $smtpHost): void
    {
        $this->smtpHost = $smtpHost;
    }

    /**
     * @return string
     */
    public function getSmtpUsername(): ?string
    {
        return $this->smtpUsername;
    }

    /**
     * @param string $smtpUsername
     */
    public function setSmtpUsername(string $smtpUsername): void
    {
        $this->smtpUsername = $smtpUsername;
    }

    /**
     * @return string
     */
    public function getSmtpPassword(): ?string
    {
        return $this->smtpPassword;
    }

    /**
     * @param string $smtpPassword
     */
    public function setSmtpPassword(string $smtpPassword): void
    {
        $this->smtpPassword = $smtpPassword;
    }

    /**
     * @return string
     */
    public function getSmtpPort(): ?string
    {
        return $this->smtpPort;
    }

    /**
     * @param string $smtpPort
     */
    public function setSmtpPort(string $smtpPort): void
    {
        $this->smtpPort = $smtpPort;
    }
}
