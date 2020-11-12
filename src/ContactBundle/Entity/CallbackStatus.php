<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * CallbackStatus
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_status_table", uniqueConstraints={
@ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"})
 *     }, indexes={
@ORM\Index(name="system_name_idx", columns={"system_name"})
 *     })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\CallbackStatusRepository")
 * @author Design studio origami <https://origami.ua>
 */
class CallbackStatus implements CallbackStatusInterface
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
     * @var integer
     *
     * @Gedmo\Versioned
     * @Gedmo\SortablePosition
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    protected $position;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="system_name", type="string", length=255, nullable=false, unique=true)
     */
    private $systemName;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->translate()->getTitle();
    }

    /**
     * @return int
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName(string $systemName): void
    {
        $this->systemName = $systemName;
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