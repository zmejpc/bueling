<?php

namespace SeoBundle\Utils;

use SeoBundle\Entity\Seo;
use SeoBundle\Entity\SeoPage;
use SeoBundle\Entity\SeoPageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SeoManager implements SeoManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var mixed
     */
    private $locale;

    /**
     * SeoManager constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
        $this->locale = $this->container->getParameter('locale');
    }

    /**
     * @return array|mixed|object|SeoPage|SeoPageInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForHomePage()
    {
        return self::getSeoForPage('homepage');
    }

    /**
     * @param string $systemName
     * @return array|mixed|object|SeoPage|SeoPageInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForPage(string $systemName)
    {
        $seoForPage = $this->em->getRepository(SeoPage::class)
            ->getSeoForPageBySystemName($systemName);

        if (empty($seoForPage)) {
            $seo = new Seo();
            $seo->translate($this->locale)->setMetaTitle($systemName);
            $seo->translate($this->locale)->setH1($systemName);
            $seo->translate($this->locale)->setBreadcrumb($systemName);
            $seoForPage = new SeoPage();
            $seoForPage->setSystemName($systemName);
            $seoForPage->setSeo($seo);
            $seo->mergeNewTranslations();
            $this->em->persist($seo);
            $this->em->persist($seoForPage);
            $this->em->flush();
            $seoForPage = $seoForPage->getSeoForPage();
        }

        return $seoForPage;
    }
}