<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ProductStatus;
use Twig\Environment;
use Ecommerce\Form\Type\Dashboard\ProductStatusType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductStatusController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.catalog.products.products', [], 'DashboardBundle') . ' > ' .
            $this->translator->trans('sidebar.products_statuses', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_PRODUCT_STATUS_LIST', 'new' => 'ROLE_PRODUCT_STATUS_CREATE',
            'edit' => 'ROLE_PRODUCT_STATUS_EDIT', 'delete' => 'ROLE_PRODUCT_STATUS_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_product_status_index', 'new' => 'dashboard_product_status_new',
            'edit' => 'dashboard_product_status_edit', 'delete' => 'dashboard_product_status_delete',
            'ajax_delete_group' => 'dashboard_product_status_ajax_delete_group', 
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-flag';
    }

    public function getFormType(): string
    {
        return ProductStatusType::class;
    }

    public function getFormElement()
    {
        return new ProductStatus();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ProductStatus::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'systemName' => $translator->trans('form.system_name', [], 'DashboardBundle'),
        ];
    }

     public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->translate()->getTitle(),
            ]),
            'systemName' => $item->getSystemName(),
        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 2,
            'order_by' => "asc"
        ];
    }

    public function onConstruct()
    {
        if( ! $inStockStatus = $this->getRepository($this->em)->findOneBy(['systemName' => 'in_stock'])) {
            $inStockStatus = $this->getFormElement();
            $inStockStatus->setSystemName('in_stock');
            $inStockStatus->translate()->setTitle('В наличии');
            $inStockStatus->mergeNewTranslations();

            $this->em->persist($inStockStatus);
            $this->em->flush();
        }

        if( ! $notInStockStatus = $this->getRepository($this->em)->findOneBy(['systemName' => 'not_in_stock'])) {
            $notInStockStatus = $this->getFormElement();
            $notInStockStatus->setSystemName('not_in_stock');
            $notInStockStatus->translate()->setTitle('Нет в наличии');
            $notInStockStatus->mergeNewTranslations();

            $this->em->persist($notInStockStatus);
            $this->em->flush();
        }
    }
}
