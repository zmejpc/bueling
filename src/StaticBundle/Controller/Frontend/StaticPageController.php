<?php

namespace StaticBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use StaticBundle\Entity\StaticPage;
use BackendBundle\Entity\Document;
use Ecommerce\Entity\ActivityArea;
use Ecommerce\Entity\Product;
use Ecommerce\Entity\Project;
use Ecommerce\Entity\Partner;

class StaticPageController extends AbstractController
{
    public function showAction(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em,
        TranslatorInterface $translator, string $slug)
    {
        $element = $em->getRepository(StaticPage::class)
            ->getStaticPageBySystemName($slug);

        if (!$element) {
            throw $this
                ->createNotFoundException(
                    $translator->trans('ui.notFound', [], 'DashboardBundle')
                );
        }

        $static = $em->getRepository(StaticContent::class)->getByPage($element->getSystemName());

        $staticContent = [];
        foreach ($static as $item) {
            $staticContent[$item->getLinkName()]['title'] = $item->translate()->getTitle();
            $staticContent[$item->getLinkName()]['text'] = $item->translate()->getDescription();
            $staticContent[$item->getLinkName()]['short_text'] = $item->translate()->getShortDescription();
            $staticContent[$item->getLinkName()]['poster'] = $item->getImg();
        }

        $static = $em->getRepository(StaticContent::class)->getByPage('homepage');
        
        foreach ($static as $item) {
            $staticContent[$item->getLinkName()]['title'] = $item->translate()->getTitle();
            $staticContent[$item->getLinkName()]['text'] = $item->translate()->getDescription();
            $staticContent[$item->getLinkName()]['short_text'] = $item->translate()->getShortDescription();
            $staticContent[$item->getLinkName()]['poster'] = $item->getImg();
        }

        $seo = $element->getSeo()->getSeoForPage();

        $breadcrumbsArr = $breadcrumbsGenerator->getBreadcrumbForHomePage();
        $breadcrumbsArr['frontend_static_page_show'][] = [
            'parameters' => ['slug' => $slug],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : '',
        ];

        $seo->og_url = $this->generateUrl('frontend_static_page_show', ['slug' => $slug]);

        $parameters = [
            'seo' => $seo,
            'element' => $element,
            'breadcrumbs' => $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'staticContent' => $staticContent,
        ];

        if($element->getSystemName() == 'about') {
            $parameters['partners'] = $em->getRepository(Partner::class)->getForFrontend();
            $parameters['area'] = $em->getRepository(ActivityArea::class)->getOneForFrontend();
            $parameters['product'] = $em->getRepository(Product::class)->getOneForFrontend();
            $parameters['project'] = $em->getRepository(Project::class)->getOneForFrontend();
            return $this->render('static/about.html.twig', $parameters);
        } elseif($element->getSystemName() == 'service') {
            $parameters['documents'] = $em->getRepository(Document::class)->getForFrontend();
            return $this->render('static/service.html.twig', $parameters);
        }

        return $this->render('static/show.html.twig', $parameters);
    }
}