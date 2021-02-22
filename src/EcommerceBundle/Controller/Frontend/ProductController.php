<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\ActivityArea;
use Ecommerce\Entity\Project;
use Ecommerce\Entity\Product;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ProductController extends AbstractController
{
    private $breadcrumbsGenerator;
    private $em;

    public function __construct(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em)
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->em = $em;
    }

    public function showProductAction(string $slug)
    {
        $product = $this->em->getRepository(Product::class)->getProductBySlug($slug);

        if (!$product) {
            throw $this->createNotFoundException();
        }

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        if ($product->hasCategories()) {

            $category = $product->getCategories()->first();
            $categorySeo = $category->getSeo()->getSeoForPage();
            $title = !empty($categorySeo->breadcrumb) ? $categorySeo->breadcrumb : $category->translate()->getTitle();

            $breadcrumbsArr['frontend_show_product_category'][] = [
                'parameters' => [
                    'slug' => $category->getSlug(),
                ],
                'title' => $title,
            ];
        }

        $breadcrumbsArr['frontend_product_show'][] = [
            'parameters' => [
                'slug' => $product->translate()->getSlug()
            ],
            'title' => $product->translate()->getTitle(),
        ];

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'seo' => $product->getSeo()->getSeoForPage(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }

    public function photoswipeAction()
    {
        return $this->render('product/photoswipe.html.twig');
    }

    public function getProductsByCategoryAction(string $slug, RequestStack $requestStack, PaginatorInterface $paginator)
    {
        $request = $requestStack->getMasterRequest();

        $category = $this->em->getRepository(ProductCategory::class)->getProductCategoryBySlug($slug);
        $activityArea = $this->em->getRepository(ActivityArea::class)->find($request->query->getInt('activity-area', 0));

        $products = $this->em->getRepository(Product::class)->getForFrontend($category, $activityArea);

        $elements = $paginator->paginate($products, $request->query->getInt('page', 1), $this->getParameter('products_per_page'));
        $elements->setTemplate('default/pagination.html.twig');
        $elements->setUsedRoute('frontend_show_product_category');

        if($request->isXmlHttpRequest()) {
            $html = $this->renderView('product_category/elements.html.twig', [
                'elements' => $elements,
            ]);

            return new JsonResponse([
                'html' => $html,
                'status' => true,
            ]);
        }

        return $this->render('product_category/elements.html.twig', [
            'elements' => $elements,
        ]);
    }
}
