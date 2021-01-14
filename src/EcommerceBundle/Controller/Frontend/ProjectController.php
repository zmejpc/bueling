<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ActivityArea;
use BackendBundle\Entity\Region;
use Ecommerce\Entity\Project;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ProjectController extends AbstractController
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

        return $this->render('project/index.html.twig', [
            'seo' => $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('projects'),
            'projects' => $this->em->getRepository(Project::class)->getForFrontend(),
            'regions' => $this->em->getRepository(Region::class)->findAll(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'activityAreas' => $this->em->getRepository(ActivityArea::class)->getForFrontend(8),
            'staticContent' => $this->em->getRepository(StaticContent::class)->getByPageForFrontend('projects'),
        ]);
    }

    public function showAction(string $slug)
    {
        $project = $this->em->getRepository(Project::class)->getBySlug($slug);

        if (!$project) {
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
                'slug' => $project->translate()->getSlug(),
            ],
            'title' => $project->translate()->getTitle(),
        ];

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'seo' => $project->getSeo()->getSeoForPage(),
            'area' => $this->em->getRepository(ActivityArea::class)->getOneForFrontend(),
            'randomProject' => $this->em->getRepository(Project::class)->getOneForFrontend(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
