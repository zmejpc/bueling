<?php

namespace ComponentBundle\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class SiteMapController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function siteMapAction(Request $request, EntityManagerInterface $em, RouterInterface $router)
    {
        $hostname = $request->getSchemeAndHttpHost();
        $urls = [];
        $urls[] = [
            'loc' => $router->generate('frontend_homepage'),
            'changefreq' => 'weekly',
            'priority' => '1.0'
        ];

        return $this->render('@Component/_site_map.xml.twig', [
            'urls' => $urls, 'hostname' => $hostname
        ]);
    }
}
