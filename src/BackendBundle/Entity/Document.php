<?php

namespace BackendBundle\Entity;

use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ComponentBundle\Entity\Position\PositionTrait;
use Doctrine\ORM\Mapping as ORM;
use SeoBundle\Entity\SeoTrait;

/**
 * Document
 *
 * @ORM\Table(name="document_table")
 * @ORM\Entity(repositoryClass="BackendBundle\Entity\Repository\DocumentRepository")
 */
class Document
{
    const YES = 1;
    const NO = 0;

    use ShowOnWebsiteTrait;
    use Timestampable;
    use PositionTrait;
    use Translatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="document", type="text", nullable=true)
     */
    private $document;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
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

    /**
     * @return string
     */
    public function getDocument(): ?string
    {
        return $this->document;
    }

    /**
     * @param string $document
     */
    public function setDocument(?string $document): void
    {
        $this->document = $document;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }
}
