<?php

namespace BackendBundle\Controller\Dashboard;

use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use NewsBundle\Entity\News;
use SeoBundle\Utils\SeoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class ArticlePreviewController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $translator;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository|\NewsBundle\Entity\Repository\NewsRepository
     */
    private $newsRepository;

    /**
     * @var BreadcrumbsGenerator
     */
    private $breadcrumbsGenerator;

    /**
     * @var SeoManager
     */
    private $seoManager;

    public function __construct(EntityManagerInterface $em, SeoManager $seoManager, TranslatorInterface $translator, BreadcrumbsGenerator $breadcrumbsGenerator)
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->seoManager = $seoManager;
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->newsRepository = $this->em->getRepository(News::class);
    }

    public function getPreviewAction(string $id, string $category = null)
    {
        $element = $this->newsRepository->find($id);

//        dump();die;

        if (!$element) {
            throw $this->createNotFoundException(
                $this->translator->trans('ui.notFound', [], 'DashboardBundle')
            );
        }

        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();
        $seo = $this->seoManager->getSeoForPage('news');
        $breadcrumbsArr['frontend_news_list'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $seo = $element->getSeo()->getSeoForPage();
        $newsCategory = $element->getNewsCategory();

        if (!is_null($newsCategory)) {
            $newsCategorySeo = $newsCategory->getSeo()->getSeoForPage();

            $breadcrumbsArr['frontend_news_category_show'][] = [
                'parameters' => [
                    'category' => $element->getNewsCategory()->translate()->getSlug()
                ],
                'title' => (!empty($newsCategorySeo) and !empty($newsCategorySeo->breadcrumb)) ? $newsCategorySeo->breadcrumb : ''
            ];

            $breadcrumbsArr['frontend_news_show_with_category'][] = [
                'parameters' => [
                    'slug' => $element->translate()->getSlug(),
                    'category' => $element->getNewsCategory()->translate()->getSlug()
                ],
                'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
            ];
        } else {
            $breadcrumbsArr['frontend_news_show'][] = [
                'parameters' => [
                    'slug' => $element->translate()->getSlug()
                ],
                'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
            ];
        }

        $element->setViews($element->getViews() + 1);
        $this->em->persist($element);
        $this->em->flush();

        $parameters = [
            'seo' => $seo,
            'element' => $element,
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr)
        ];

        return $this->render('news/show.html.twig', $parameters);
    }
}