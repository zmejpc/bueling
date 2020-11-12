<?php

namespace ContactBundle\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\Callback;
use ContactBundle\Form\Type\Dashboard\CallbackType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackController extends CRUDController
{
    public function topNavigationMenu(EntityManagerInterface $em)
    {
        return $this->render('@SupportCenterCallback/dashboard/top_navigation_menu/_top_navigation_menu.html.twig', [
            'countNewCallbackRequests' => $em->getRepository(Callback::class)->countNewCallbackRequests(),
        ]);
    }

    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.callback.callback', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.callback.callback_form', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CALLBACK_LIST', 'new' => null,
            'edit' => 'ROLE_CALLBACK_EDIT', 'delete' => 'ROLE_CALLBACK_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_callback_index', 'new' => null,
            'edit' => 'dashboard_callback_edit', 'delete' => 'dashboard_callback_delete',
            'ajax_delete_group' => 'dashboard_callback_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'dashboard_stat.new_requests';
    }

    public function getFormType(): string
    {
        return CallbackType::class;
    }

    public function getFormElement()
    {
        return new Callback();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Callback::class);

        return $repository;
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 0,
            'order_by' => "desc"
        ];
    }

    // Елементы для index
    public function getElementsForIndexDashboard(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, Environment $twig)
    {
        $repository = $this->getRepository($em);

        $iTotalRecords = $repository->countAllElementsForIndexDashboard();
        $elements = $repository->allElementsForIndexDashboard($request);

        $helper = $this->dashboardManager->helperForIndexDashboard($iTotalRecords, $elements);
        $pagination = $helper['pagination'];
        $records = $helper['records'];

        $listElements = $this->getListElementsForIndexDashboard($translator);

        foreach ($pagination as $element) {
            $id = $element->getId();
            $records["data"][$id][] = $twig->render('@Dashboard/default/list/_checkbox.html.twig', ['id' => $element->getId()]);
            $records["data"][$id][] = $element->getId();

            foreach ($listElements as $key => $elementForList) {
                if($key == 'status'){
                    $records["data"][$id][] = ($element->getStatus()) ? $element->getStatus()->translate()->getTitle() : '';
                }else{
                    $records = $this->dashboardManager->helperForGetElementsForIndexDashboard($id, $records, $key, $elementForList, $element);
                }
            }

            $records["data"][$id][] = $twig->render('@Dashboard/default/list/_actions_edit_delete.html.twig', [
                'action_edit_url' => (is_null($this->getRouteElements()['edit'])) ? null : $this->generateUrl($this->getRouteElements()['edit'], ['id' => $element->getId()]),
                'action_delete_url' => (is_null($this->getRouteElements()['delete'])) ? null : $this->generateUrl($this->getRouteElements()['delete'], ['id' => $element->getId()]),
                'action_edit_role' => $this->getGrantedRoles()['edit'],
                'action_delete_role' => $this->getGrantedRoles()['delete'],
            ]);
        }
        $records["data"] = array_values($records["data"]);

        return $records;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'name' => $translator->trans('ui.first_name', [], 'DashboardBundle'),
            'phone' => $translator->trans('ui.phone_number', [], 'DashboardBundle'),
            'status' => $translator->trans('ui.status', [], 'DashboardBundle'),
            'message' => $translator->trans('ui.message', [], 'DashboardBundle')
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'name' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getName(),
            ]),
            'phone' => $item->getPhone(),
            'status' => $item->getStatus() ? $item->getStatus()->translate()->getTitle() : '',
            'message' => $item->getMessage(),
        ];
    }

    public function customActionInNewAction($object)
    {
        $object->setUpdatedBy($this->getUser());
        return $object;
    }

    public function customActionInEditAction($object)
    {
        $object->setUpdatedBy($this->getUser());
        return $object;
    }
}
