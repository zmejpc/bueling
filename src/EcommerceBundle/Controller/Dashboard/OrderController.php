<?php

namespace Ecommerce\Controller\Dashboard;

use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ShippingMethod;
use Ecommerce\Entity\Order;
use UserBundle\Entity\User;
use Ecommerce\Entity\OrderHasProduct;
use Ecommerce\Form\Type\Dashboard\OrderType;
use Ecommerce\Utils\CartManager;
use Ecommerce\Entity\ExchangeRate;
use Ecommerce\Entity\PaymentMethod;
use Ecommerce\Entity\Currency;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UploadBundle\Services\FileHandler;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class OrderController extends CRUDController
{
    public function topNavigationMenu(EntityManagerInterface $em)
    {
        $orders = [];
        return $this->render('@Ecommerce/dashboard/top_navigation_menu/_top_navigation_menu.html.twig', [
            'countNewOrders' => $em->getRepository(Order::class)->countNewOrders(),
            'orders' => $orders
        ]);
    }

    public function getHeadTitle(): string
    {
        return $this->translator->trans('sidebar.sales.orders.orders', [], 'DashboardBundle');
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_ORDER_LIST', 'new' => 'ROLE_ORDER_CREATE',
            'edit' => 'ROLE_ORDER_EDIT', 'delete' => 'ROLE_ORDER_DELETE',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_order_index', 'new' => 'dashboard_order_new',
            'edit' => 'dashboard_order_edit', 'delete' => 'dashboard_order_delete',
            'ajax_delete_group' => 'dashboard_order_ajax_delete_group',
            'export_delovod' => 'dashboard_order_export_delovod'
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-basket-loaded';
    }

    public function getFormType(): string
    {
        return OrderType::class;
    }

    public function getFormElement()
    {
        return new Order();
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Order::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'number_alias_id' => ['title' => $translator->trans('ui.order_number', [], 'DashboardBundle'), 'sort' => 'desc'],
            'orderDate' => $translator->trans('ui.order_date', [], 'DashboardBundle'),
            'updatedAt' => $translator->trans('ui.last_updated', [], 'DashboardBundle'),
            'name' => $translator->trans('ui.fio', [], 'DashboardBundle'),
            'phone' => $translator->trans('ui.phone_number', [], 'DashboardBundle'),
            'email' => $translator->trans('ui.email', [], 'DashboardBundle'),
            'userComment' => $translator->trans('ui.order_comment', [], 'DashboardBundle'),
            'totalPrice' => $translator->trans('ui.sum', [], 'DashboardBundle'),
            'status' => $translator->trans('ui.order_state', [], 'DashboardBundle'),
            'isInSession' => 'Оформленные / В корзине ',
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'number_alias_id' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getId(),
            ]),
            'orderDate' => $item->getOrderDate() ? $item->getOrderDate()->format('Y-m-d H:i') : '-',
            'updatedAt' => $item->getUpdatedAt()->format('Y-m-d H:i'),
            'name' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => ucfirst($item->getName()) . '<br>' . ucfirst($item->getSurname()),
            ]),
            'phone' => $twig->render('@Dashboard/default/crud/list/element/_link.html.twig', [
                'href' => $this->generateUrl($this->getRouteElements()['edit'], ['id' => $item->getId()]),
                'title' => $item->getPhone(),
            ]),
            'email' => $item->getEmail(),
            'userComment' => $item->getUserComment(),
            'totalPrice' => $item->getTotalPrice() . ' ' . $item->getCurrency()->translate()->getShortTitle(),
            'status' => $item->getStatus() ? $item->getStatus()->translate()->getTitle() : '',
            'kt_row_class' => '',
        ];
    }

    public function getListFilterElementsForIndexDashboard()
    {
        return [
            'isInSession' => [
                'options' => [
                    [
                        'title' => 'Оформленные',
                        'value' => 0,
                        'defaultValue' => true,
                    ],
                    [
                        'title' => 'В корзине',
                        'value' => 1,
                    ],
                ],
            ],
        ];
    }

    public function getDefaultQuery()
    {
        return [
            'filter' => [
                'isInSession' => 0,
            ]
        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'number_alias_id',
            'order_by' => "desc"
        ];
    }

    public function getPortletBodyTemplateForForm(): string
    {
        return '@Ecommerce/dashboard/order/form/_portlet_body.html.twig';
    }

    public function getShowActionsColumn()
    {
        return true;
    }

    public function getNovaPoshtaFormAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(self::getFormType(), self::getFormElement(), []);
        $form->submit($data);

        return $this->render('@Ecommerce/dashboard/order/form/_novaPoshtaFormData.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function getCurrency(EntityManagerInterface $em)
    {
        $currency = $this->get('session')->get('currency');
        $currencyRepository = $em->getRepository(Currency::class);

        if (is_null($currency)) {
            $currency = $currencyRepository->getBySystemName($this->getParameter('currency'));
        } else {
            $currency = $currencyRepository->getBySystemName($currency);
        }

        return $currency;
    }

    public function customActionInNewAction($item)
    {
       
        $currenciesRates = $this->em->getRepository(ExchangeRate::class)->getCurrencyRates();
        $item->recalculateTotalPrice($currenciesRates);

        return$item;
    }

    public function customActionInEditAction($item)
    {
        $currenciesRates = $this->em->getRepository(ExchangeRate::class)->getCurrencyRates();
        $item->recalculateTotalPrice($currenciesRates);

        return$item;
    }

    public function getUserOrdersInfoForForm(User $user)
    {
        $orders = $this->em->getRepository(Order::class)->findOrdersByUser($user->getId());

        return $this->render('order/dashboard/_user_orders.html.twig', [
            'orders' => $orders,
            'reduceFunc' => function($carry, $v) {return $carry + $v->getTotalPrice();},
        ]);
    }
}
