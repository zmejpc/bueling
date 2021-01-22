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

        $activityArea = $this->em->getRepository(ActivityArea::class)->find($request->query->getInt('activity-area', 0));
        $region = $this->em->getRepository(Region::class)->find($request->query->getInt('region', 0));
        $projects = $this->em->getRepository(Project::class)->getForFrontend(1000, $activityArea, $region);

        $elements = $paginator->paginate($projects, $request->query->getInt('page', 1), $this->getParameter('products_per_page'));
        $elements->setTemplate('default/pagination.html.twig');
        $elements->setUsedRoute('frontend_projects');

        if($request->isXmlHttpRequest()) {
            $html = $this->renderView('project/elements.html.twig', [
                'elements' => $elements,
            ]);

            return new JsonResponse([
                'html' => $html,
                'status' => true,
            ]);
        }

        return $this->render('project/index.html.twig', [
            'seo' => $seo,
            'elements' => $elements,
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

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'seo' => $project->getSeo()->getSeoForPage(),
            'area' => $this->em->getRepository(ActivityArea::class)->getOneForFrontend(),
            'randomProject' => $this->em->getRepository(Project::class)->getOneForFrontend(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
