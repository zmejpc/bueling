<?php

namespace Ecommerce\Entity;

use Component\ComponentBundle\Entity\__callTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;

/**
 * ProductStatus
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="product_status_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"})
 *     }, indexes={
 *     @ORM\Index(name="system_name_idx", columns={"system_name", "entity_class"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ProductStatusRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class ProductStatus
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
        use YesOrNoTrait;

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
}