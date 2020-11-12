<?php

namespace ComponentBundle\Controller\Frontend;

use SeoBundle\Utils\SeoManager;
use ComponentBundle\Utils\SearchManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Ecommerce\Entity\ExchangeRate;
use Ecommerce\Utils\CartManager;
use Ecommerce\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use ComponentBundle\Form\Type\Frontend\SearchFormType;
use ComponentBundle\Form\Type\Frontend\SearchShopFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class SearchController extends AbstractController
{
    /**
     * @var SeoManager
     */
    protected $seoManager;

    /**
     * @var BreadcrumbsGenerator
     */
    protected $breadcrumbsGenerator;

    /**
     * @var SearchManager
     */
    protected $searchManager;

    /**
     * @var PaginatorInterface
     */
    protected $paginator;

    protected $currency;

    /**
     * SearchController constructor.
     * @param SeoManager $seoManager
     * @param BreadcrumbsGenerator $breadcrumbsGenerator
     * @param SearchManager $searchManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(
        SeoManager $seoManager, BreadcrumbsGenerator $breadcrumbsGenerator,
        SearchManager $searchManager, PaginatorInterface $paginator, EntityManagerInterface $em, SessionInterface $session,
        ContainerInterface $container
    )
    {
        $this->paginator = $paginator;
        $this->seoManager = $seoManager;
        $this->searchManager = $searchManager;
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->em = $em;
        $this->currency = $session->get('currency');
        $currencyRepository = $em->getRepository(Currency::class);
        if (is_null($this->currency)) {
            $this->currency = $currencyRepository->getBySystemName($container->getParameter('currency'));
        } else {
            $this->currency = $currencyRepository->getBySystemName($this->currency);
        }
    }


    /**
     * @return array
     */
    public static function getSearchShopChoices(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public static function getSearchShopEmptyData(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function getEntitiesForSearch(): array
    {
        $results = [
            'key' => [
                'entity' => 'Entity path',
                'fields' => [
                    'key2' => [
                        'is_translate' => true,
                        'field' => 'keykey2'
                    ]
                ]
            ]];

        return $results;
    }

    /**
     * @return string|null
     */
    public function getEntityForSearch(): ?string
    {
        return null;
    }

    /**
     * @return Response
     */
    public function initSearchFormAction()
    {
        $form = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_from_header_save'),
        ]);

        return $this->render('search/_init_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param string|null $str
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchSaveAction(Request $request, string $str = null)
    {
        $form = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_from_header_save'),
        ]);

        $form->handleRequest($request);

        $searchStr = null;

        if ($form->isSubmitted() and $form->isValid()) {
            if (!empty($form->getData()['search'])) {
                $searchStr = $form->getData()['search'];
            }
        } else {
            $searchStr = $str;
        }

        return $this->redirectToRoute('frontend_search_index', [
            'searchStr' => $searchStr,
        ]);
    }

    /**
     * @param int $page
     * @param int $countInPage
     * @param string|null $searchStr
     * @return mixed
     * @throws \Exception
     */
    public function getSearchResultsAction(int $page, int $countInPage, string $searchStr = null)
    {
        $seo = $this->seoManager->getSeoForPage('search');

        $form = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_index'),
        ]);

        $form->get('search')->setData($searchStr);

        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();
        $breadcrumbsArr['frontend_search_index'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $breadcrumbs = $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);

        $parameters = [
            'seo' => $seo,
            'form' => $form->createView(),
            'breadcrumbs' => $breadcrumbs,
            'searchStr' => $searchStr,
            'page' => $page,
            'countInPage' => $countInPage
        ];

        return $this->render('search/index.html.twig', $parameters);
    }

    /**
     * @param Request $request
     * @param int $page
     * @param int $countInPage
     * @param string|null $searchStr
     * @param bool $fromTwig
     * @return mixed
     */
    public function getResultsForSearchPageAction(
        Request $request, int $page, int $countInPage, string $searchStr = null, bool $fromTwig = false, CartManager $cartManager
    )
    {
        $results = $this->searchManager->searchResultsOnlyInTitle(
            $this->getEntitiesForSearch(), $searchStr, $this->getEntityForSearch(), null, true
        );

        $elements = $this->paginator->paginate($results, $request->query->getInt('page', $page), $countInPage);

        $elements->setTemplate('search/_pagination_without_type.html.twig');
        $elements->setUsedRoute('frontend_search_shop_index');
        $elements->setParam('searchStr', $searchStr);
        $elements->setParam('countInPage', $countInPage);

        $parameters = [
            'elements' => $elements,
            'page' => $elements->getPage() ? $elements->getPage() + 1 : 2,
            'countInPage' => $countInPage,
            'searchStr' => $searchStr,
            'total' => $elements->getTotalItemCount(),
            'isKnpPaginationRender' => !($page == $elements->getPageCount()),
            'currenciesRates' => $this->em->getRepository(ExchangeRate::class)->getCurrencyRates(),
            'currency' => $this->currency,
            'currentCart' => $cartManager->getCurrentCart($this->currency, $this->getUser()),
        ];

        if ($fromTwig or $request->isXmlHttpRequest()) {
            if($page > 1) {
                return $this->render('search/_paginated_elements.html.twig', $parameters);
            } else {
                return new JsonResponse([
                    'elements' => $this->renderView('search/_paginated_elements.html.twig', $parameters),
                    'count' => $elements->getTotalItemCount(),
                ]);
            }
        } else {
            $response = new Response(Response::HTTP_OK);

            $response->headers->set('X-Robots-Tag', 'noindex');

            return $this->render('search/paginated_elements.html.twig', $parameters, $response);
        }
    }

    /**
     * @return int
     */
    public function getCountElementsForHints(): int
    {
        return 5;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function searchGetHintsAction(Request $request)
    {
        $form = $this->createForm(SearchFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_index'),
        ]);

        $form->handleRequest($request);

        $searchStr = '';

        if (!empty($form->getData()) and !empty($form->getData()['search'])) {
            $searchStr = $form->getData()['search'];
        }

        $results = $this->searchManager->searchResultsOnlyInTitle(
            $this->getEntitiesForSearch(), $searchStr, $this->getEntityForSearch(),
            $this->getCountElementsForHints(), true
        );

        $elements = $this->paginator->paginate(
            $results, $request->query->getInt('page', 1), $this->getCountElementsForHints()
        );

        $parameters = [
            'elements' => $elements,
        ];

        return $this->render('search/_get_hints.html.twig', $parameters);
    }

    /**
     * @return Response
     */
    public function initSearchShopFormAction()
    {
        $form = $this->createForm(SearchShopFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_from_header_save'),
        ]);

        $form->get('type')->setData(\FrontendBundle\Controller\SearchController::getSearchShopEmptyData());

        return $this->render('search/_init_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param string|null $type
     * @param string|null $str
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function searchShopSaveAction(Request $request, string $type = null, string $str = null)
    {
        $form = $this->createForm(SearchShopFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_from_header_save'),
        ]);

        $form->handleRequest($request);

        $searchStr = null;
        $searchType = null;

        if ($form->isSubmitted() and $form->isValid()) {
            if (!empty($form->getData()['search'])) {
                $searchStr = $form->getData()['search'];
            }
            if (!empty($form->getData()['type'])) {
                $searchType = $form->getData()['type'];
            }
        } else {
            $searchStr = $str;
            $searchType = $type;
        }

        return $this->redirectToRoute('frontend_search_from_header_with_search_str', [
            'search_str' => $searchStr,
            'type' => $searchType
        ]);
    }

    /**
     * @param string|null $type
     * @param string|null $search_str
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSearchShopResultsAction(string $type = null, string $search_str = null)
    {
        $seo = $this->seoManager->getSeoForPage('search');

        $form = $this->createForm(SearchShopFormType::class, null, [
            'action' => $this->generateUrl('frontend_search_from_header_save'),
        ]);

        $form->get('search')->setData($search_str);
        $form->get('type')->setData($type);

        $breadcrumbsArr = $this->breadcrumbsGenerator->getBreadcrumbForHomePage();
        $breadcrumbsArr['frontend_search_from_header_with_search_str'][] = [
            'parameters' => [],
            'title' => (!empty($seo) and !empty($seo->breadcrumb)) ? $seo->breadcrumb : ''
        ];

        $breadcrumbs = $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);

        return $this->render('search/index.html.twig', [
            'seo' => $seo,
            'form' => $form->createView(),
            'breadcrumbs' => $breadcrumbs,
            'searchStr' => $search_str,
            'searchType' => $type
        ]);
    }
}
