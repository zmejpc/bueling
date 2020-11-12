<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ExchangeRate;
use Ecommerce\Form\Type\Dashboard\ExchangeRateType;
use SeoBundle\Entity\Seo;
use DashboardBundle\Controller\CRUDController;
use Ecommerce\Entity\Product;
use Ecommerce\Form\Type\Dashboard\ProductType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ExchangeRatesController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.currencies.exchange_rates', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_CURRENCY_LIST', 'new' => null,
            'edit' => 'ROLE_CURRENCY_EDIT', 'delete' => null,
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_exchange_rates_index', 'new' => null,
            'edit' => 'dashboard_exchange_rates_edit', 'delete' => null,
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'fa fa-balance-scale';
    }

    public function getFormType(): string
    {
        return ExchangeRateType::class;
    }

    public function getFormElement()
    {
        return new ExchangeRate();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(ExchangeRate::class);

        return $repository;
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'sourceCurrency' => $translator->trans('ui.source_currency', [], 'DashboardBundle'),
            'targetCurrency' => $translator->trans('ui.target_currency', [], 'DashboardBundle'),
            'ratio' => 'Курс',
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'sourceCurrency' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getSourceCurrency()->translate()->getTitle(),
            ]),
            'targetCurrency' => $item->getTargetCurrency()->translate()->getTitle(),
            'ratio' => $item->getRatio(),
        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 100,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 4,
            'order_by' => "asc"
        ];
    }

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/exchange_rate/form/_portlet_body.html.twig';
    }
}
