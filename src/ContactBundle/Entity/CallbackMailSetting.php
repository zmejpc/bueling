<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * CallbackMailSetting
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_mail_setting_table")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\CallbackMailSettingRepository")
 * @author Design studio origami <https://origami.ua>
 */
class CallbackMailSetting
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    /**
     * @param $method
     * @param $arguments
     * @return mixed|null
     */
    public function __call($method, $arguments)
    {
        if ($method == '_action') {
            return null;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @Gedmo\Versioned
     */
    private $id;

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
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSmtpHost()
    {
        return $this->smtpHost;
    }

    /**
     * @param string $smtpHost
     */
    public function setSmtpHost($smtpHost)
    {
        $this->smtpHost = $smtpHost;
    }

    /**
     * @return string
     */
    public function getSmtpUsername()
    {
        return $this->smtpUsername;
    }

    /**
     * @param string $smtpUsername
     */
    public function setSmtpUsername($smtpUsername)
    {
        $this->smtpUsername = $smtpUsername;
    }

    /**
     * @return string
     */
    public function getSmtpPassword()
    {
        return $this->smtpPassword;
    }

    /**
     * @param string $smtpPassword
     */
    public function setSmtpPassword($smtpPassword)
    {
        $this->smtpPassword = $smtpPassword;
    }

    /**
     * @return string
     */
    public function getSmtpPort()
    {
        return $this->smtpPort;
    }

    /**
     * @param string $smtpPort
     */
    public function setSmtpPort($smtpPort)
    {
        $this->smtpPort = $smtpPort;
    }
}