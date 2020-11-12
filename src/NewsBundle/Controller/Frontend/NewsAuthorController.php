<?php

namespace NewsBundle\Controller\Frontend;

use SeoBundle\Utils\SeoManager;
use NewsBundle\Entity\NewsAuthor;
use Doctrine\ORM\EntityManagerInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsAuthorController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

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
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->translator = $translator;
        $this->seoManager = $seoManager;
    }

    /**
     * @param string $author
     * @param int|null $page
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function helperForShowAction(string $author, int $page = null)
    {
        $newsAuthor = $this->em->getRepository(NewsAuthor::class)->getElementBySlug($author);

        if (!$newsAuthor) {
            throw $this->createNotFoundException(
                $this->translator->trans('ui.notFound', [], 'DashboardBundle')
            );
        }

        $seo = $newsAuthor->getSeo()->getSeoForPage();
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

        $breadcrumbsArr['frontend_news_by_author_list'][] = [
            'parameters' => [
                'author' => $author
            ],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $parameters = [
            'seo' => $seo,
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
            'author' => $newsAuthor
        ];

        return $parameters;
    }

    public function byAuthorWithStandardPaginationAction(
        string $author, int $page, int $countInPage
    )
    {
        $parameters = self::helperForShowAction($author, $page);
        $parameters['page'] = $page;
        $parameters['countInPage'] = $countInPage;

        return $this->render('news_author/index.html.twig', $parameters);
    }
}