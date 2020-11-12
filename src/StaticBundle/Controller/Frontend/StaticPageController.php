<?php

namespace StaticBundle\Controller\Frontend;

use StaticBundle\Entity\StaticPage;
use StaticBundle\Entity\StaticContent;
use Doctrine\ORM\EntityManagerInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            $staticContent[$item->getLinkName()]['shortDescription'] = $item->translate()->getShortDescription();
            $staticContent[$item->getLinkName()]['description'] = $item->translate()->getDescription();
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

        if($element->getSystemName() == 'about_us') {
            return $this->render('static/about_us.html.twig', $parameters);
        }

        return $this->render('static/show.html.twig', $parameters);
    }
}