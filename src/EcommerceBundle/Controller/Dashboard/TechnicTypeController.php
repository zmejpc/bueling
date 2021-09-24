<?php

namespace Ecommerce\Controller\Dashboard;

use Symfony\Contracts\Translation\TranslatorInterface;
use Ecommerce\Form\Type\Dashboard\TechnicTypeType;
use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\TechnicType;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class TechnicTypeController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Виды техники';
    }

    public function getHeadTitleForEdit($object)
    {
        return '';
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_LIST', 'new' => 'ROLE_PRODUCT_CREATE',
            'edit' => 'ROLE_PRODUCT_EDIT', 'delete' => 'ROLE_PRODUCT_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_technic_type_index', 'new' => 'dashboard_technic_type_new',
            'edit' => 'dashboard_technic_type_edit', 'delete' => 'dashboard_technic_type_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-barcode';
    }

    public function getFormType(): string
    {
        return TechnicTypeType::class;
    }

    public function getFormElement()
    {
        return new TechnicType();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(TechnicType::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'showOnWebsite' => [
                'title' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),
                'width' => 80
            ],
        ];
    }
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item, 'fieldName' => 'showOnWebsite',
            ]),
        ];
    }

    public function getShowActionsColumn()
    {
        return true;
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 50,
            'lengthMenu' => '10, 20, 25, 50',
            'order_column' => 4,
            'order_by' => "asc"
        ];
    }
}
