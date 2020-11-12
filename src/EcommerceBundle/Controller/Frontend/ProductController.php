<?php

namespace Ecommerce\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use SeoBundle\Utils\SeoManager;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\Product;
use SeoBundle\Entity\SeoPage;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use ComponentBundle\Utils\BreadcrumbsGenerator;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ProductController extends AbstractController
{
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

    public function showProductAction(EntityManagerInterface $em, TranslatorInterface $translator, string $slug,SessionInterface $session, BreadcrumbsGenerator $breadcrumbsGenerator)
    {
        $product = $em->getRepository(Product::class)->getProductBySlug($slug);

        if (!$product) {
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $static = $em->getRepository(StaticContent::class)->getByPage('product');

        $staticContent = [];
        foreach ($static as $item) {
            $staticContent[$item->getLinkName()]['shortDescription'] = $item->translate()->getShortDescription();
            $staticContent[$item->getLinkName()]['description'] = $item->translate()->getDescription();
        }

        $seo = $product->getSeo()->getSeoForPage();

        $breadcrumbsArr = [];
        $seoHomepage = $em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        if($product->hasCategories()) {
            $category = $product->getCategories()->first();
            if($category->getShowOnWebsite()) {
                $breadcrumbsArr['frontend_show_product_category'][] = [
                    'parameters' => [
                        'slug' => $category->getSlug()
                    ],
                    'title' => $category->translate()->getTitle(),
                ];
            }
        }

        $breadcrumbsArr['frontend_product_show'][] = [
            'parameters' => [
                'slug' => $product->translate()->getSlug()
            ],
            'title' => $product->translate()->getTitle(),
        ];

        return $this->render('product/index.html.twig', [
            'seo' => $seo,
            'product' => $product,
            'staticContent' => $staticContent,
            'breadcrumbs' => $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }

    public function photoswipeAction()
    {
        return $this->render('product/_photoswipe.html.twig');
    }
}
