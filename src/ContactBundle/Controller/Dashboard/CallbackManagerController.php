<?php

namespace ContactBundle\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use ContactBundle\Entity\CallbackManager;
use ContactBundle\Form\Type\Dashboard\CallbackManagerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackManagerController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.callback.callback', [], 'DashboardBundle') . ' > '
            . $this->translator->trans('sidebar.callback.callback_settings.callback_settings', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.callback.callback_settings.callback_managers', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CALLBACK_MANAGER_LIST', 'new' => 'ROLE_CALLBACK_MANAGER_CREATE',
            'edit' => 'ROLE_CALLBACK_MANAGER_EDIT', 'delete' => 'ROLE_CALLBACK_MANAGER_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_callback_manager_index', 'new' => 'dashboard_callback_manager_new',
            'edit' => 'dashboard_callback_manager_edit', 'delete' => 'dashboard_callback_manager_delete',
            'ajax_delete_group' => 'dashboard_callback_manager_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-users';
    }

    public function getFormType(): string
    {
        return CallbackManagerType::class;
    }

    public function getFormElement()
    {
        return new CallbackManager();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(CallbackManager::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'name' => $translator->trans('ui.first_name', [], 'DashboardBundle'),
            'email' => $translator->trans('ui.email', [], 'DashboardBundle'),
            'isSendForEmail' => $translator->trans('form.is_send_for_email', [], 'DashboardBundle'),
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'name' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getName(),
            ]),
            'email' => $item->getEmail(),
            'isSendForEmail' => $item->getIsSendForEmail(),
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
                if ($key == 'isSendForEmail') {
                    $records["data"][$id][] = $twig->render('@Dashboard/default/list/_yes_no.html.twig', ['element' => $element->getIsSendForEmail()]);
                } else {
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
}