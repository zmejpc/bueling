<?php

namespace SeoBundle\Utils;

use SeoBundle\Entity\SeoPage;
use SeoBundle\Entity\SeoPageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface SeoManagerInterface
{
    /**
     * SeoManager constructor.
     * @param EntityManagerInterface $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManagerInterface $em, ContainerInterface $container);

    /**
     * @return array|mixed|object|SeoPage|SeoPageInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForHomePage();

    /**
     * @param string $systemName
     * @return array|mixed|object|SeoPage|SeoPageInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForPage(string $systemName);
}