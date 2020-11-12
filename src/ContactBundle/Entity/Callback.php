<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;
use ComponentBundle\Entity\RemoteUrl\RemoteUrlTrait;

/**
 * Callback
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_table")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\CallbackRepository")
 * @author Design studio origami <https://origami.ua>
 */
class Callback implements CallbackInterface
{
    use ORMBehaviors\Timestampable\Timestampable;
    use RemoteUrlTrait;

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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var \ContactBundle\Entity\CallbackStatus
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="ContactBundle\Entity\CallbackStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="callback_status_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $status;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by_id", referencedColumnName="id")
     * })
     */
    private $updatedBy;

    /**
     * @var \UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by_id", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    public function setUpdatedBy(User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \UserBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
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
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return CallbackStatus
     */
    public function getStatus(): ?CallbackStatus
    {
        return $this->status;
    }

    /**
     * @param CallbackStatus $status
     */
    public function setStatus(CallbackStatus $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}