<?php

namespace DashboardBundle\Controller;

use Twig\Environment;
use SeoBundle\Utils\SeoManager;
use BackendBundle\Event\FilterEvent;
use UploadBundle\Services\FileHandler;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Utils\DashboardManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class CRUDController extends AbstractController implements CRUDControllerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * @var DashboardManager
     */
    protected $dashboardManager;

    /**
     * @var
     */
    protected $seoManager;

    /**
     * @var int|null
     */
    protected $templateNumber = null;

    protected $eventDispatcher;

    /**
     * CRUDController constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param DashboardManager $dashboardManager
     * @param AuthorizationCheckerInterface $authChecker
     * @param Environment $twig
     * @param SeoManager $seoManager
     */
    public function __construct(
        EntityManagerInterface $em, TranslatorInterface $translator, DashboardManager $dashboardManager,
        AuthorizationCheckerInterface $authChecker, Environment $twig, SeoManager $seoManager, EventDispatcherInterface $eventDispatcher
    )
    {
        if (empty($this->getConfigForIndexDashboard()) or
            !array_key_exists('pageLength', $this->getConfigForIndexDashboard()) or
            !array_key_exists('lengthMenu', $this->getConfigForIndexDashboard()) or
            !array_key_exists('order_column', $this->getConfigForIndexDashboard()) or
            !array_key_exists('order_by', $this->getConfigForIndexDashboard())
        ) {
            throw new \InvalidArgumentException('Method getConfigForIndexDashboard wrong!');
        }

        if (empty($this->getGrantedRoles()) or empty($this->getRouteElements()) or
            !array_key_exists('index', $this->getGrantedRoles()) or
            !array_key_exists('new', $this->getGrantedRoles()) or
            !array_key_exists('edit', $this->getGrantedRoles()) or
            !array_key_exists('delete', $this->getGrantedRoles()) or

            !array_key_exists('index', $this->getRouteElements()) or
            !array_key_exists('new', $this->getRouteElements()) or
            !array_key_exists('edit', $this->getRouteElements()) or
            !array_key_exists('delete', $this->getRouteElements())
        ) {
            throw new \InvalidArgumentException('Method getGrantedRoles or getRouteElements wrong!');
        }

        $this->dashboardManager = $dashboardManager;
        $this->seoManager = $seoManager;
        $this->em = $em;
        $this->translator = $translator;
        $this->authChecker = $authChecker;
        $this->twig = $twig;
        $this->eventDispatcher = $eventDispatcher;
        $this->templateNumber = DashboardConfig::getTemplateNumber();

        $this->onConstruct();
    }

    public function onConstruct()
    {

    }

    /**
     * @param Request $request
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function getElementsForIndexAction(Request $request)
    {
        $sort = $request->get('sort');

        if (is_null($sort)) {
            $sort = [
                'field' => $this->getConfigForIndexDashboard()['order_column'],
                'sort' => $this->getConfigForIndexDashboard()['order_by']
            ];
        }

        $dataTable = array_merge(['pagination' => [], 'sort' => [], 'query' => []], [
            'pagination' => $request->get('pagination'),
            'sort' => $sort, 'query' => $request->get('query')
        ]);

        $elements = $this->getRepository($this->em)->allElementsForIndexDashboard(
            $dataTable, $this->getListElementsForIndexDashboard($this->translator)
        );

        $helper = $this->dashboardManager
            ->helperForIndexDashboard($elements, $this->getConfigForIndexDashboard(), $dataTable);

        $meta = [
            'page' => $helper['meta']['page'], 'pages' => $helper['meta']['page'],
            'perpage' => $helper['meta']['perPage'], 'total' => $helper['pagination']->getPaginationData()['totalCount']
        ];

        $data = [];

        foreach ($helper['pagination'] as $item) {
            $id = $item->getId();

            if ($this->isCustomActionForList()) {
                $data[] = array_merge($this->createDataForList($item, $this->twig), [
                    'id' => $id,
                    'Actions' => $this->customActionForList($item),
                ]);
            } else {
                $data[] = array_merge($this->createDataForList($item, $this->twig), [
                    'id' => $id,
                    'Actions' =>
                        $this->twig->render('@Dashboard/default/crud/list/_actions_edit_delete.html.twig', [
                            'action_edit_url' => is_null($this->getRouteElements()['edit']) ? null :
                                $this->generateUrl($this->getRouteElements()['edit'], ['id' => $id]),
                            'action_edit_role' => $this->getGrantedRoles()['edit'],
                            'action_delete_url' => is_null($this->getRouteElements()['delete']) ? null :
                                $this->generateUrl($this->getRouteElements()['delete'], ['id' => $id]),
                            'action_delete_role' => $this->getGrantedRoles()['delete'],
                        ])
                ]);
            }
        }

        $result = [
            'meta' => $meta + ['sort' => $helper['meta']['sort'], 'field' => $helper['meta']['field']],
            'data' => $data
        ];

        return $result;
    }

    /**
     * @return array
     */
    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'id',
            'order_by' => "asc"
        ];
    }

    /**
     * @return bool
     */
    public function isUseWidgetsForList(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isUseSubTableForList(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isCustomActionForList(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isMinimizeAsideMenuElements(): bool
    {
        return false;
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listAction(Request $request)
    {
        if (false === $this->authChecker->isGranted($this->getGrantedRoles()['index'])) {
            throw new AccessDeniedException(
                $this->translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        if ($request->isXmlHttpRequest()) {
            $event = new FilterEvent();
            $event->setRequest($request);
            $this->eventDispatcher->dispatch('dashboard.list.filter.listener', $event);

            return new JsonResponse($this->getElementsForIndexAction($request));
        }

        $parameters = [
            'templateNumber' => $this->templateNumber,
            'headTitle' => $this->getHeadTitle(),
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isUseWidgetsForList' => $this->isUseWidgetsForList(),
//            'isUseSubTableForList' => $this->isUseSubTableForList(),
            'grantedRoleForNew' => $this->getGrantedRoles()['new'],
            'configForListDashboard' => $this->getConfigForIndexDashboard(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'listElements' => $this->getListElementsForIndexDashboard($this->translator),
            'listFilterElements' => $this->getListFilterElementsForIndexDashboard(),
            'routeForGetElementsForNew' => is_null($this->getRouteElements()['new']) ? null :
                $this->generateUrl($this->getRouteElements()['new']),
            'widgetsForList' => $this->isUseWidgetsForList() == true ? $this->renderWidgetsForList() : null,
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index']),
            'routeForEditElementsGroup' => empty($this->getRouteElements()['edit_group']) ? null :
                $this->generateUrl($this->getRouteElements()['edit_group']),
            'routeForAjaxDeleteElementsGroup' => empty($this->getRouteElements()['ajax_delete_group']) ? null :
                $this->generateUrl($this->getRouteElements()['ajax_delete_group']),
            'routeForAjaxSort' => empty($this->getRouteElements()['ajax_sort']) ? null :
                $this->generateUrl($this->getRouteElements()['ajax_sort']),
            'showActionsColumn' => $this->getShowActionsColumn(),
            'defaultQuery' => $this->getDefaultQuery(),
            'portletHeadTools' => $this->getPortletHeadTools(),
            'headBlock' => $this->getHeadBlock(),
        ];

        return $this->render('@Dashboard/default/crud/list/index.html.twig', $parameters);
    }

    public function getPortletHeadTools()
    {
        return '';
    }

    public function getHeadBlock()
    {
        return '';
    }

    public function getDefaultQuery()
    {
        return [];
    }

    public function getShowActionsColumn()
    {
        return true;
    }

    public function getListFilterElementsForIndexDashboard()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array
    {
//        return [
//            'useSvg' => true,
//            'icon' => '',
//            'svg' => ''
//        ];
        return [];
    }

    /**
     * @return string
     */
    public function renderWidgetsForList(): string
    {
        return '';
    }

    /**
     * @param $item
     * @return string
     */
    public function customActionForList($item): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string
    {
        return '@Dashboard/default/crud/form/_body.html.twig';
    }

    /**
     * @param Request $request
     * @param FileHandler $fileHandler
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FileHandler $fileHandler)
    {
        if (false === $this->authChecker->isGranted($this->getGrantedRoles()['new'])) {
            throw new AccessDeniedException(
                $this->translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        $object = $this->getFormElement();

        $form = $this->createForm($this->getFormType(), $object, [
            'action' => $this->generateUrl($this->getRouteElements()['new']),
            'method' => 'POST', 'grantedRoles' => $this->getGrantedRoles()
        ]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $object = $this->customActionInNewAction($object);
                $this->em->persist($object);
                try {
                    $fileHandler->saveFileOrImageFromForm($object, $form->all());
                    $this->em->flush();
                    $this->addFlash('flash_create_success',
                        $this->translator
                            ->trans('flashes.flash.flash_create_success', [], 'DashboardBundle')
                    );
                    $this->addFlash('js_flash_create_success',
                        $this->translator
                            ->trans('flashes.flash.flash_create_success', [], 'DashboardBundle')
                    );
                    $clickedButton = $form->getClickedButton();
                    if ($clickedButton) {
                        $clickedButton = $clickedButton->getName();
                        if ('createAndCreate' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['new']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['new']
                            );
                        } elseif ('createAndList' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['index']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['index']
                            );
                        }
                    }
                    return $this->redirectToRoute(
                        is_null($this->getRouteElements()['edit']) ? 'dashboard_homepage_index' :
                            $this->getRouteElements()['edit'], ['id' => $object->getId()]
                    );
                } catch (\Exception $exception) {
                    $this->addFlash('flash_create_error', $exception->getMessage());
                    $this->addFlash('js_flash_create_error', $exception->getMessage());
                }
            }
        }

        $parameters = [
            'headTitle' => $this->getHeadTitle(),
            'templateNumber' => $this->templateNumber,
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index']),
            'form' => $form->createView(),
            'portletBodyTemplateForForm' => $this->getPortletBodyTemplateForForm()
        ];

        return $this->render('@Dashboard/default/crud/new_edit/index.html.twig', $parameters);
    }

    /**
     * @param Request $request
     * @param FileHandler $fileHandler
     * @param int $id
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, FileHandler $fileHandler, int $id)
    {
        if (false === $this->authChecker->isGranted($this->getGrantedRoles()['edit'])) {
            throw new AccessDeniedException(
                $this->translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        $repository = $this->getRepository($this->em);
        $object = $repository->getElementByIdForDashboardEditOrDeleteFormAction($id);

        if (!$object) {
            throw $this->createNotFoundException(
                $this->translator->trans('ui.notFound', [], 'DashboardBundle')
            );
        }

        $form = $this->createForm($this->getFormType(), $object, [
            'action' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $id]),
            'method' => 'POST', 'grantedRoles' => $this->getGrantedRoles()
        ]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $object = $this->customActionInEditAction($object);
                $this->em->persist($object);
                try {
                    $fileHandler->saveFileOrImageFromForm($object, $form->all());
                    $this->em->flush();
                    $this->addFlash('flash_edit_success',
                        $this->translator
                            ->trans('flashes.flash.flash_edit_success', [], 'DashboardBundle')
                    );
                    $this->addFlash('js_flash_edit_success',
                        $this->translator
                            ->trans('flashes.flash.flash_edit_success', [], 'DashboardBundle')
                    );
                    $clickedButton = $form->getClickedButton();
                    if ($clickedButton) {
                        $clickedButton = $clickedButton->getName();
                        if ('saveAndCreate' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['new']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['new']
                            );
                        } elseif ('saveAndList' === $clickedButton) {
                            return $this->redirectToRoute(
                                is_null($this->getRouteElements()['index']) ?
                                    'dashboard_homepage_index' : $this->getRouteElements()['index']
                            );
                        }
                    }

                    $referer = $request->headers->get('referer');
                    return new RedirectResponse($referer);

                } catch (\Exception $exception) {
                    $this->addFlash('flash_edit_error', $exception->getMessage());
                    $this->addFlash('js_flash_edit_error', $exception->getMessage());
                }
            } else {
                $str = '';
                foreach ($form->getErrors() as $key => $error) {
                    $str .= $error->getCause()->getPropertyPath() . '- ';
                    $str .= $error->getMessage();
                    $str .= '; ';
                }

                $this->addFlash('flash_edit_error', $str);
                $this->addFlash('js_flash_edit_error', $str);
            }
        }

        $parameters = [
            'headTitle' => $this->getHeadTitle().$this->getHeadTitleForEdit($object),
            'templateNumber' => $this->templateNumber,
            'portletHeadIcon' => $this->getPortletHeadIcon(),
            'isMinimizeAsideMenuElements' => $this->isMinimizeAsideMenuElements(),
            'routeForGetElementsForIndex' => is_null($this->getRouteElements()['index']) ? null :
                $this->generateUrl($this->getRouteElements()['index']),
            'form' => $form->createView(),
            'portletBodyTemplateForForm' => $this->getPortletBodyTemplateForForm(),
            'routeForGetElementsForDelete' => (is_null($this->getRouteElements()['delete'])) ? null :
                $this->generateUrl($this->getRouteElements()['delete'], ['id' => $id]),
            'action_delete_role' => $this->getGrantedRoles()['delete']
        ];

        return $this->render('@Dashboard/default/crud/new_edit/index.html.twig', $parameters);
    }

    public function getHeadTitleForEdit($object)
    {
        return '';
    }

    /**
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $id)
    {
        if (false === $this->authChecker->isGranted($this->getGrantedRoles()['delete'])) {
            throw new AccessDeniedException(
                $this->translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        $repository = $this->getRepository($this->em);
        $object = $repository->getElementByIdForDashboardEditOrDeleteFormAction($id);

        if (!$object) {
            throw $this->createNotFoundException(
                $this->translator->trans('ui.notFound', [], 'DashboardBundle')
            );
        }

        $object = $this->customActionInDeleteAction($object);

        if (!is_null($object)) {
            try {
                $this->em->remove($object);
                $this->em->flush();
                $this->addFlash('flash_delete_success',
                    $this->translator->trans('flashes.flash.flash_delete_success', [], 'DashboardBundle')
                );
                $this->addFlash('js_flash_delete_success',
                    $this->translator->trans('flashes.flash.flash_delete_success', [], 'DashboardBundle')
                );
            } catch (\Exception $exception) {
                $this->addFlash('flash_delete_error', $exception->getMessage());
                $this->addFlash('js_flash_delete_error', $exception->getMessage());
            }
        }

        return $this->redirectToRoute(
            is_null($this->getRouteElements()['index']) ? 'dashboard_homepage_index' :
                $this->getRouteElements()['index']
        );
    }

    public function ajaxDeleteGroupAction(Request $request)
    {
        if (false === $this->authChecker->isGranted($this->getGrantedRoles()['delete'])) {
            throw new AccessDeniedException(
                $this->translator->trans('ui.accessDenied', [], 'DashboardBundle')
            );
        }

        try {
            $this->getRepository($this->em)->deleteByIds((array)$request->request->get('ids'));    
            $result['status'] = 'success';
        } catch (\Exception $e) {
            $result['status'] = 'error';
        }

        return new JsonResponse($result);
    }

    public function ajaxSortAction(Request $request)
    {
        $data = $request->request->get('data');
        $items = $this->getRepository($this->em)->findByIds((array) array_keys($data));

        foreach($items as $item) {
            $item->setPosition($data[$item->getId()]);
            $this->em->persist($item);
            $this->em->flush();
        }
        
        exit();
    }

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInNewAction($object)
    {
        return $object;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInEditAction($object)
    {
        return $object;
    }

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInDeleteAction($object)
    {
        return $object;
    }
}