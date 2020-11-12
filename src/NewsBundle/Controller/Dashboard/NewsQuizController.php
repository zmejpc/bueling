<?php

namespace NewsBundle\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use NewsBundle\Entity\News;
use NewsBundle\Entity\NewsQuiz;
use NewsBundle\Form\Type\Dashboard\NewsQuizType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use UploadBundle\Services\FileHandler;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizController extends CRUDController
{
    /**
     * @param TranslatorInterface $translator
     * @return string
     */
    public function getHeadTitle(): string
    {
        return $this->translator->trans('ui.quizzes', [], 'DashboardBundle');
    }

    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_NEWS_LIST', 'new' => 'ROLE_NEWS_CREATE',
            'edit' => 'ROLE_NEWS_EDIT', 'delete' => 'ROLE_NEWS_DELETE',
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_news_quiz_index', 'new' => 'dashboard_news_quiz_new',
            'edit' => 'dashboard_news_quiz_edit', 'delete' => 'dashboard_news_quiz_delete',
        ];
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(NewsQuiz::class);

        return $repository;
    }

    /**
     * @param EntityManagerInterface $em
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getNewsRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(News::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'position',
            'order_by' => "ASC"
        ];
    }

    /**
     * @param TranslatorInterface $translator
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
        ];
    }

    /**
     * @param $item
     * @param Environment $twig
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $item->translate()->getTitle(),
            'position' => $item->getPosition(),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ])
        ];
    }

    public function getFormElement()
    {
        $new = new NewsQuiz();

        return $new;
    }

    public function getFormType(): string
    {
        return NewsQuizType::class;
    }

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string
    {
        return '@News/dashboard/quiz/form/_portlet_body.html.twig';
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param Environment $twig
     * @return array
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    private function getCustomElementsForIndexAction(
        Request $request, EntityManagerInterface $em,
        TranslatorInterface $translator, Environment $twig, $news
    )
    {
        $repository = $this->getRepository($em);

        $dataTable = array_merge(['pagination' => [], 'sort' => [], 'query' => []], [
            'pagination' => $request->get('pagination'),
            'sort' => $request->get('sort'), 'query' => $request->get('query')
        ]);

        // total items
        $total = $repository->customCountAllElementsForIndexDashboard(
            $dataTable, $this->getListElementsForIndexDashboard($translator), $news
        );

        $elements = $repository->customAllElementsForIndexDashboard(
            $dataTable, $this->getListElementsForIndexDashboard($translator), $news
        );

        $helper = $this->dashboardManager->helperForIndexDashboard(
            $elements, $this->getConfigForIndexDashboard(), $dataTable
        );

        $meta = [
            'page' => $helper['meta']['page'], 'pages' => $helper['meta']['page'],
            'perpage' => $helper['meta']['perPage'], 'total' => $total,
        ];

        $data = [];

        foreach ($helper['pagination'] as $item) {
            $id = $item->getId();

            $data[] = array_merge($this->createDataForList($item, $twig), [
                'id' => $id,
                'Actions' =>
                    $twig->render('@Dashboard/default/crud/list/_actions_edit_delete.html.twig', [
                        'action_edit_url' => is_null($this->getRouteElements()['edit']) ? null :
                            $this->generateUrl($this->getRouteElements()['edit'], [
                                'id' => $id,
                                'news' => $news->getId()
                            ]),
                        'action_edit_role' => $this->getGrantedRoles()['edit'],
                        'action_delete_url' => is_null($this->getRouteElements()['delete']) ? null :
                            $this->generateUrl($this->getRouteElements()['delete'], [
                                'id' => $id,
                                'news' => $news->getId()
                            ]),
                        'action_delete_role' => $this->getGrantedRoles()['delete'],
                    ])
            ]);
        }

        $result = [
            'meta' => $meta + ['sort' => $helper['meta']['sort'], 'field' => $helper['meta']['field']],
            'data' => $data
        ];

        return $result;
    }

    public function customListAction(
        Request $request, EntityManagerInterface $em, TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker, Environment $twig, int $news
    )
    {
        if (false === $authChecker->isGranted($this->getGrantedRoles()['index'])) {
            throw new AccessDeniedException(
                $translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        $repository = $this->getNewsRepository($em);
        $news = $repository->getElementByIdForDashboardEditOrDeleteFormAction($news);

        if (!$news) {
            throw $this
                ->createNotFoundException($translator->trans('ui.notFound', [], 'DashboardBundle'));
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($this
                ->getCustomElementsForIndexAction($request, $em, $translator, $twig, $news));
        }

        $parameters = [
            'templateNumber' => $this->templateNumber,
            'headTitle' => $news->translate()->getTitle() . ' - ' . $this->getHeadTitle($translator)  ,
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isUseWidgetsForList' => $this->isUseWidgetsForList(),
            'isUseSubTableForList' => $this->isUseSubTableForList(),
            'grantedRoleForNew' => $this->getGrantedRoles()['new'],
            'configForListDashboard' => $this->getConfigForIndexDashboard(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'listElements' => $this->getListElementsForIndexDashboard($translator),
            'routeForGetElementsForNew' => is_null($this->getRouteElements()['new']) ? null :
                $this->generateUrl($this->getRouteElements()['new'], [
                    'news' => $news->getId()
                ]),

            'widgetsForList' => $this->isUseWidgetsForList() == true ? $this->renderWidgetsForList($em) : null,
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index'], [
                    'news' => $news->getId()
                ]),
            'routeForGetElementsForNewsIndex' => $this->generateUrl('dashboard_news_index')
        ];

        return $this->render('@News/dashboard/quiz/crud/new_edit/index.html.twig', $parameters);
    }

    public function customNewAction(
        Request $request, FileHandler $fileHandler,
        EntityManagerInterface $em, TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker, int $news
    )
    {
        if (false === $authChecker->isGranted($this->getGrantedRoles()['new'])) {
            throw new AccessDeniedException($translator
                ->trans('ui.accessDenied', [], 'DashboardBundle'));
        }

        $repository = $this->getNewsRepository($em);
        $news = $repository->getElementByIdForDashboardEditOrDeleteFormAction($news);

        if (!$news) {
            throw $this
                ->createNotFoundException($translator->trans('ui.notFound', [], 'DashboardBundle'));
        }

        $object = $this->getFormElement();
        $object->setNews($news);

        $form = $this->createForm($this->getFormType(), $object, [
            'action' => $this->generateUrl($this->getRouteElements()['new'], [
                'news' => $news->getId()
            ]),
            'method' => 'POST', 'grantedRoles' => $this->getGrantedRoles()
        ]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $object = $this->customActionInNewAction($object);
                $em->persist($object);
                try {
                    // $fileHandler->saveFileOrImageFromForm($em, $object, $form->all());
                    $em->flush();
                    $this->addFlash('flash_create_success',
                        $translator->trans('flashes.flash.flash_create_success', [], 'DashboardBundle')
                    );

                    $clickedButton = $form->getClickedButton();
                    if ($clickedButton) {
                        $clickedButton = $clickedButton->getName();
                        if ('createAndEdit' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['edit']) ? 'dashboard_homepage_index' :
                                    $this->getRouteElements()['edit'], [
                                    'id' => $object->getId(), 'news' => $news->getId()
                                ]
                            );
                        } elseif ('createAndCreate' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['new']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['new'], [
                                    'news' => $news->getId()
                                ]
                            );
                        } elseif ('createAndList' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['index']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['index'], [
                                    'news' => $news->getId()
                                ]
                            );
                        }
                    }
                } catch (\Exception $exception) {
                    $this->addFlash('flash_create_error', $exception->getMessage());
                }
            }
        }

        $parameters = [
            'headTitle' => $news->translate()->getTitle() . ' - ' . $this->getHeadTitle($translator),
            'templateNumber' => $this->templateNumber,
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index'], [
                    'news' => $news->getId()
                ]),
            'form' => $form->createView(),
            'portletBodyTemplateForForm' => $this->getPortletBodyTemplateForForm()
        ];

        return $this->render('@Dashboard/default/crud/new_edit/index.html.twig', $parameters);
    }

    public function customEditAction(
        Request $request, FileHandler $fileHandler,
        EntityManagerInterface $em, TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker, int $id, int $news
    )
    {
        if (false === $authChecker->isGranted($this->getGrantedRoles()['edit'])) {
            throw new AccessDeniedException($translator
                ->trans('ui.accessDenied', [], 'DashboardBundle'));
        }

        $repository = $this->getRepository($em);
        $object = $repository->getCustomElementByIdForDashboardEditOrDeleteFormAction($id, $news);

        if (!$object) {
            throw $this
                ->createNotFoundException($translator->trans('ui.notFound', [], 'DashboardBundle'));
        }

        $form = $this->createForm($this->getFormType(), $object, [
            'action' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $id, 'news' => $news]),
            'method' => 'POST', 'grantedRoles' => $this->getGrantedRoles()
        ]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $object = $this->customActionInEditAction($em, $object);
                $em->persist($object);
                try {
                    $fileHandler->saveFileOrImageFromForm($em, $object, $form->all());
                    $em->flush();
                    $this->addFlash('flash_edit_success',
                        $translator->trans('flashes.flash.flash_edit_success', [], 'DashboardBundle')
                    );
                    $clickedButton = $form->getClickedButton();
                    if ($clickedButton) {
                        $clickedButton = $clickedButton->getName();
                        if ('saveAndEdit' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['edit']) ? 'dashboard_homepage_index' :
                                    $this->getRouteElements()['edit'], [
                                    'id' => $object->getId(), 'news' => $news
                                ]
                            );
                        } elseif ('saveAndCreate' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['new']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['new'], [
                                    'news' => $news
                                ]
                            );
                        } elseif ('saveAndList' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['index']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['index'], [
                                    'news' => $news
                                ]
                            );
                        }
                    }
                } catch (\Exception $exception) {
                    $this->addFlash('flash_edit_error', $exception->getMessage());
                }
            }
        }

        $parameters = [
            'headTitle' => $object->getNews()->translate()->getTitle() . ' - ' . $this->getHeadTitle($translator),
            'templateNumber' => $this->templateNumber,
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index'], ['news' => $news]),
            'form' => $form->createView(),
            'portletBodyTemplateForForm' => $this->getPortletBodyTemplateForForm(),
            'routeForGetElementsForDelete' => (is_null($this->getRouteElements()['delete'])) ? null :
                $this->generateUrl($this->getRouteElements()['delete'], ['id' => $id, 'news' => $news]),
            'action_delete_role' => $this->getGrantedRoles()['delete'],
        ];

        return $this->render('@Dashboard/default/crud/new_edit/index.html.twig', $parameters);
    }

    public function customDeleteAction(
        EntityManagerInterface $em, TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker, int $id, int $news
    )
    {
        if (false === $authChecker->isGranted($this->getGrantedRoles()['delete'])) {
            throw new AccessDeniedException($translator
                ->trans('ui.accessDenied', [], 'DashboardBundle'));
        }

        $repository = $this->getRepository($em);
        $object = $repository->getCustomElementByIdForDashboardEditOrDeleteFormAction($id, $news);

        if (!$object) {
            throw $this
                ->createNotFoundException($translator->trans('ui.notFound', [], 'DashboardBundle'));
        }

        $object = $this->customActionInDeleteAction($object);

        try {
            $em->remove($object);
            $em->flush();
            $this->addFlash('flash_delete_success',
                $translator->trans('flashes.flash.flash_delete_success', [], 'DashboardBundle')
            );
        } catch (\Exception $exception) {
            $this->addFlash('flash_delete_error', $exception->getMessage());
        }

        return $this
            ->redirectToRoute(is_null($this->getRouteElements()['index']) ? 'dashboard_homepage_index' :
                $this->getRouteElements()['index'], ['news' => $news]
            );
    }
}