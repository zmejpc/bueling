<?php

namespace Ecommerce\Entity;

use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * TechnicType
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="technic_type_table", indexes={
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website", "entity_class"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\TechnicTypeRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class TechnicType
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use ShowOnWebsiteTrait;

    public const YES = 1;
    public const NO = 0;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @Gedmo\Versioned
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getClassName()
    {
        return __NAMESPACE__ . '\\' . (new \ReflectionClass($this))->getShortName();
    }
}
