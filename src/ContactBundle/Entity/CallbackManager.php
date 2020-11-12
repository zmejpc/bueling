<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * CallbackManager
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_manager_table", uniqueConstraints={
@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})
 *     }, indexes={
@ORM\Index(name="email_idx", columns={"email"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="email")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\CallbackManagerRepository")
 * @author Design studio origami <https://origami.ua>
 */
class CallbackManager implements CallbackManagerInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

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
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="is_send_for_email", type="integer", nullable=false)
     */
    private $isSendForEmail = self::YES;

    public function __toString()
    {
        return (string)$this->getName() . ' - ' . $this->getEmail();
    }

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getIsSendForEmail(): bool
    {
        return $this->isSendForEmail;
    }

    /**
     * @param bool $isSendForEmail
     */
    public function setIsSendForEmail(bool $isSendForEmail): void
    {
        $this->isSendForEmail = $isSendForEmail;
    }

    /**
     * @return array|mixed
     */
    public static function yesOrNo()
    {
        return [
            self::YES => "form.yes",
            self::NO => "form.no"
        ];
    }

    /**
     * @return array|mixed
     */
    public static function yesOrNoForm()
    {
        return [
            "form.yes" => self::YES,
            "form.no" => self::NO,
        ];
    }
}