<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Ecommerce\Entity\ProductCategory;
use StaticBundle\Entity\StaticPage;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\Position\PositionTrait;

/**
 * Faq
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="faq_table")
 * @ORM\Entity(repositoryClass="FrontendBundle\Entity\Repository\FaqRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class Faq
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
        use YesOrNoTrait;
        use ShowOnWebsiteTrait;
        use PositionTrait;

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
        return (string)$this->translate()->getQuestion();
    }
}