<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Services\ProjectFilter;
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
    private $filter;
    private $em;

    public function __construct(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em, TranslatorInterface $translator, ProjectFilter $filter)
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->translator = $translator;
        $this->filter = $filter;
        $this->em = $em;
    }

    public function listAction(Request $request, PaginatorInterface $paginator)
    {
        $seo = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('projects');

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_projects'][] = [
            'parameters' => [],
            'title' => $seo->breadcrumb ?? $this->translator->trans('menu.projects', [], 'FrontendBundle'),
        ];

        $filterData = $this->filter->getFilterData($request->getLocale());
        $selectedilter = $this->filter->getSelectedFilter($request->query->all(), $filterData);
        $countInFilter = $this->filter->getCountInFilter($selectedilter, $filterData);

        $projects = $this->em->getRepository(Project::class)->getByFilter($selectedilter);

        $elements = $paginator->paginate($projects, $request->query->getInt('page', 1), $this->getParameter('products_per_page'));
        $elements->setTemplate('default/pagination.html.twig');
        $elements->setUsedRoute('frontend_projects');

        if($request->isXmlHttpRequest()) {
            $html = $this->renderView('project/elements.html.twig', [
                'elements' => $elements,
            ]);

            $formHTML = $this->renderView('project/filter.html.twig', [
                'countInFilter' => $countInFilter,
                'selectedilter' => $selectedilter,
                'filterData' => $filterData,
            ]);

            return new JsonResponse([
                'formHTML' => $formHTML,
                'html' => $html,
                'status' => true,
            ]);
        }

        return $this->render('project/index.html.twig', [
            'seo' => $seo,
            'elements' => $elements,
            'filterData' => $filterData,
            'selectedilter' => $selectedilter,
            'countInFilter' => $countInFilter,
            'regions' => $this->em->getRepository(Region::class)->findAll(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'staticContent' => $this->em->getRepository(StaticContent::class)->getByPageForFrontend('projects'),
        ]);
    }

    public function showAction(string $slug)
    {
        $project = $this->em->getRepository(Project::class)->getBySlug($slug);

        if (!$project) {
            throw $this->createNotFoundException($this->translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $seo = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('projects');

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_projects'][] = [
            'parameters' => [],
            'title' => $seo->breadcrumb ?? $this->translator->trans('menu.projects', [], 'FrontendBundle'),
        ];

        $breadcrumbsArr['frontend_show_project'][] = [
            'parameters' => [
                'slug' => $project->translate()->getSlug(),
            ],
            'title' => $project->translate()->getTitle(),
        ];

        $prevProject = $this->em->getRepository(Project::class)->getNeighborForFrontend($project, 'prev');
        $nextProject = $this->em->getRepository(Project::class)->getNeighborForFrontend($project, 'next');

        $relatedProjects = $this->em->getRepository(Project::class)->getRelatedForFrontend($project);

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'prevProject' => $prevProject,
            'nextProject' => $nextProject,
            'relatedProjects' => $relatedProjects,
            'seo' => $project->getSeo()->getSeoForPage(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
