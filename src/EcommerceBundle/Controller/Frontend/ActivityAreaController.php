<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ActivityArea;
use Ecommerce\Entity\Project;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ActivityAreaController extends AbstractController
{
    private $breadcrumbsGenerator;
    private $translator;
    private $em;

    public function __construct(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->translator = $translator;
        $this->em = $em;
    }

    public function listAction()
    {
        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_activity_areas'][] = [
            'parameters' => [],
            'title' => $this->translator->trans('menu.activity_areas', [], 'FrontendBundle'),
        ];

        return $this->render('activity_area/index.html.twig', [
            'seo' => $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('activity_areas'),
            'areas' => $this->em->getRepository(ActivityArea::class)->getForFrontend(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'staticContent' => $this->em->getRepository(StaticContent::class)->getByPageForFrontend('activity_areas'),
        ]);
    }

    public function showAction(string $slug)
    {
        $area = $this->em->getRepository(ActivityArea::class)->getBySlug($slug);

        if (!$area) {
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_activity_areas'][] = [
            'parameters' => [],
            'title' => $this->translator->trans('menu.activity_areas', [], 'FrontendBundle'),
        ];

        $breadcrumbsArr['frontend_show_activity_area'][] = [
            'parameters' => [
                'slug' => $area->translate()->getSlug(),
            ],
            'title' => $area->translate()->getTitle(),
        ];

        return $this->render('activity_area/show.html.twig', [
            'area' => $area,
            'seo' => $area->getSeo()->getSeoForPage(),
            'projects' => $this->em->getRepository(Project::class)->getForFrontend(2),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
