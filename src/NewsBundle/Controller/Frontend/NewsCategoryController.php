<?php

namespace NewsBundle\Controller\Frontend;

use SeoBundle\Utils\SeoManager;
use NewsBundle\Entity\NewsCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class NewsCategoryController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|\NewsBundle\Entity\Repository\NewsCategoryRepository
     */
    private $newsCategoryRepository;

    /**
     * @var BreadcrumbsGenerator
     */
    private $breadcrumbsGenerator;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SeoManager
     */
    private $seoManager;

    /**
     * NewsCategoryController constructor.
     * @param EntityManagerInterface $em
     * @param BreadcrumbsGenerator $breadcrumbsGenerator
     * @param TranslatorInterface $translator
     * @param SeoManager $seoManager
     */
    public function __construct(
        EntityManagerInterface $em, BreadcrumbsGenerator $breadcrumbsGenerator, TranslatorInterface $translator,
        SeoManager $seoManager
    )
    {
        $this->em = $em;
        $this->newsCategoryRepository = $this->em->getRepository(NewsCategory::class);
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->translator = $translator;
        $this->seoManager = $seoManager;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function initMenu(Request $request): Response
    {
        return $this->render('news_category/_menu.html.twig', [
            'elements' => $this->newsCategoryRepository->getElementsForMenu(),
            'request' => $request
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function initFooterMenu(Request $request): Response
    {
        return $this->render('news_category/_news_footer_menu.html.twig', [
            'elements' => $this->newsCategoryRepository->getElementsForMenu(),
            'request' => $request
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function initAllMenu(Request $request): Response
    {
        return $this->render('news_category/_all_menu.html.twig', [
            'elements' => $this->newsCategoryRepository->getElements(),
            'request' => $request
        ]);
    }

    /**
     * @param string $category
     * @param int|null $page
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function helperForShowAction(string $category, int $page = null)
    {
        $newsCategory = $this->newsCategoryRepository->getElementBySlug($category);

        if (!$newsCategory) {
            throw $this->createNotFoundException(
                $this->translator->trans('ui.notFound', [], 'DashboardBundle')
            );
        }

        $newsCategorySlug = $newsCategory->translate($newsCategory->getDefaultLocale())->getSlug();

        $seo = $newsCategory->getSeo()->getSeoForPage();
        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();

        $news = $this->seoManager->getSeoForPage('news');
        $breadcrumbsArr['frontend_news_list'][] = [
            'parameters' => [],
            'title' => (!empty($news) and !empty($news->breadcrumb)) ? $news->breadcrumb : ''
        ];

        if (!is_null($page)) {
            if ($page > 1) {
                $seo->h1 = $seo->h1 . ' - №' . $page;
                $seo->title = $seo->title . ' - №' . $page;
            }
        }

        $breadcrumbsArr['frontend_news_category_show'][] = [
            'parameters' => [
                'category' => $newsCategorySlug
            ],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $parameters = [
            'seo' => $seo,
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'newsCategory' => $newsCategory,
            'category' => $category,
            'news' => $newsCategory->getNews()
        ];

        return $parameters;
    }

    /**
     * @param string $category
     * @param int $page
     * @param int $countInPage
     * @param int|null $month
     * @param int|null $year
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showWithStandardPaginationAction(
        string $category, int $page, int $countInPage, int $month = null, int $year = null
    ): Response
    {
        $parameters = self::helperForShowAction($category, $page);
        $parameters['page'] = $page;
        $parameters['countInPage'] = $countInPage;
        $parameters['month'] = $month;
        $parameters['year'] = $year;

        return $this->render('news_category/index.html.twig', $parameters);
    }

    /**
     * @param string $category
     * @param int|null $month
     * @param int|null $year
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function showAction(string $category, int $month = null, int $year = null): Response
    {
        $parameters = self::helperForShowAction($category);
        $parameters['month'] = $month;
        $parameters['year'] = $year;

        return $this->render('news/index.html.twig', $parameters);
    }
}