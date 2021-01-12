<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
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
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_product_show'][] = [
            'parameters' => [
                'slug' => $product->translate()->getSlug()
            ],
            'title' => $product->translate()->getTitle(),
        ];

        return $this->render('product/index.html.twig', [
            'product' => $product,
            'seo' => $product->getSeo()->getSeoForPage(),
            'area' => $this->em->getRepository(ActivityArea::class)->getOneForFrontend(),
            'project' => $this->em->getRepository(Project::class)->getOneForFrontend(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }

    public function photoswipeAction()
    {
        return $this->render('product/photoswipe.html.twig');
    }

    public function getProductsByCategoryAction(EntityManagerInterface $em, int $category_id, bool $is_ajax = false, int $page = 0)
    {
        $filter = $this->get('session')->get('filter');
        $filter['category'] = [$category_id];

        $per_page = $this->getParameter('products_per_page');

        $products = $em->getRepository(Product::class)
            ->getProductsByFilter($filter, $per_page, $per_page * $page);

        $currentCart = $orderController->getCurrentCart();

        if($is_ajax) {
            return $this->renderView('product/_category_elements.html.twig', [
                'products' => $products,
                'per_page' => $per_page,
                'page' => $page,
                'is_ajax' => true,
            ]);
        }

        return $this->render('product/_category_elements.html.twig', [
            'products' => $products,
            'noFilter' => $noFilter,
            'per_page' => $per_page,
            'page' => $page,
        ]);
    }
}
