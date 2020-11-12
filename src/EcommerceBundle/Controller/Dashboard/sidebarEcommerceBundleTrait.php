<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\Order;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarEcommerceBundleTrait
{   
    private function sidebarEcommerceBundle()
    {
    	$menu = self::headingSidebar('', 'E-commerce', [
            self::eCommerceBundleRoles()['order']
        ], [], 'DashboardBundle');

        if(!is_null($menu)) {
        	$countNewOrders = $this->getDoctrine()->getManager()->getRepository(Order::class)->countNewOrders();

        	$orders = self::itemSidebar(['ROLE_ORDER_CREATE_EDIT'], ['order/edit/'], ['dashboard_order_index', 'dashboard_order_new'], 'flaticon-box', true, $countNewOrders, 'badge-primary', 'sidebar.sales.orders.orders', [], 'dashboard_order_index');

        	(!is_null($orders)) ? $menu['items'][] = $orders : null;

	        $productCategories = self::itemSidebar(['ROLE_PRODUCT_CATEGORY_CREATE_EDIT'], [], ['dashboard_product_category_index', 'dashboard_product_category_new', 'dashboard_product_category_edit'], 'fa fa-sitemap', false, null, null, 'sidebar.catalog.categories', [], 'dashboard_product_category_index');

            (!is_null($productCategories)) ? $menu['items'][] = $productCategories : null;

            $productCapsules = self::itemSidebar(['ROLE_PRODUCT_CAPSULE_CREATE_EDIT'], [], ['dashboard_product_capsule_index', 'dashboard_product_capsule_new', 'dashboard_product_capsule_edit'], 'fa fa-sitemap', false, null, null, 'Капсулы', [], 'dashboard_product_capsule_index');

            (!is_null($productCapsules)) ? $menu['items'][] = $productCapsules : null;

            $productCollaborations = self::itemSidebar(['ROLE_PRODUCT_COLLABORATION_CREATE_EDIT'], [], ['dashboard_product_collaboration_index', 'dashboard_product_collaboration_new', 'dashboard_product_collaboration_edit'], 'fa fa-sitemap', false, null, null, 'Коллаборации', [], 'dashboard_product_collaboration_index');

            (!is_null($productCollaborations)) ? $menu['items'][] = $productCollaborations : null;

            $products = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product/edit/'], ['dashboard_product_index', 'dashboard_product_new', 'dashboard_product_edit'], 'fa fa-barcode', false, null, null, 'sidebar.catalog.products.products', [], 'dashboard_product_index');

            (!is_null($products)) ? $menu['items'][] = $products : null;

	        $settings = self::itemSidebar(['ROLE_ORDER_CREATE_EDIT', 'ROLE_ORDER_STATUS_CREATE_EDIT', 'ROLE_ORDER_MANAGER_CREATE_EDIT'], ['order/status/edit/', 'order/manager/edit/', 'order/edit/'], ['dashboard_order_status_index', 'dashboard_order_status_new', 'dashboard_order_manager_index', 'dashboard_order_manager_new', 'dashboard_shipping_method_index', 'dashboard_shipping_method_new', 'dashboard_payment_method_index', 'dashboard_payment_method_new', 'dashboard_exchange_rates_index', 'dashboard_currency_index', 'dashboard_currency_new', 'dashboard_product_discount_index', 'dashboard_product_discount_new', 'dashboard_product_size_index', 'dashboard_product_status_index', 'dashboard_product_status_new', 'dashboard_size_status_index', 'dashboard_size_status_new'], 'flaticon-settings', false, 0, 'badge-primary', 'sidebar.sales.orders.orders_settings.orders_settings', [
	        	 self::itemSidebar(['ROLE_SHIPPING_METHODS_CREATE_EDIT'], ['shipping-method/edit/'], ['dashboard_shipping_method_index', 'dashboard_shipping_method_new'], 'fa fa-bus', false, null, null, 'sidebar.payment_shipping.shipping_methods', [], 'dashboard_shipping_method_index'),
	        	 self::itemSidebar(['ROLE_PAYMENT_METHODS_CREATE_EDIT'], ['payment-method/edit/'], ['dashboard_payment_method_index', 'dashboard_payment_method_new'], 'flaticon-symbol', false, null, null, 'sidebar.payment_shipping.payment_methods', [], 'dashboard_payment_method_index'),
	        	self::itemSidebar(['ROLE_CURRENCY_CREATE_EDIT'], [], ['dashboard_exchange_rates_index'], 'fa fa-balance-scale', false, null, null, 'sidebar.currencies.exchange_rates', [], 'dashboard_exchange_rates_index'),
            	self::itemSidebar(['ROLE_CURRENCY_CREATE_EDIT'], [], ['dashboard_currency_index', 'dashboard_currency_new'], 'flaticon-globe', false, null, null, 'sidebar.currencies.currency', [], 'dashboard_currency_index'),
                self::itemSidebar(['ROLE_PRODUCT_SIZE_CREATE_EDIT'], ['product-size/edit/'], ['dashboard_product_size_index', 'dashboard_product_size_new'], 'flaticon-squares-4', false, null, null, 'Размеры', [], 'dashboard_product_size_index'),
                self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product-discount/edit/'], ['dashboard_product_discount_index', 'dashboard_product_discount_new'], 'flaticon-add-label-button', false, null, null, 'Скидки', [], 'dashboard_product_discount_index'),
                self::itemSidebar(['ROLE_ORDER_STATUS_CREATE_EDIT'], ['order/status/edit/'], ['dashboard_order_status_index', 'dashboard_order_status_new'], 'flaticon-stopwatch', false, null, null, 'sidebar.sales.orders.orders_settings.orders_statuses', [], 'dashboard_order_status_index'),
               self::itemSidebar(['ROLE_PRODUCT_STATUS_CREATE_EDIT'], ['product/status/edit/'], ['dashboard_product_status_index', 'dashboard_product_status_new'], 'flaticon-stopwatch', false, null, null, 'sidebar.products_statuses', [], 'dashboard_product_status_index'),
                self::itemSidebar(['ROLE_SIZE_STATUS_CREATE_EDIT'], ['size/status/edit/'], ['dashboard_size_status_index', 'dashboard_size_status_new'], 'flaticon-stopwatch', false, null, null, 'sidebar.size_statuses', [], 'dashboard_size_status_index'),
                self::itemSidebar(['ROLE_ORDER_MANAGER_CREATE_EDIT'], ['order/manager/edit/'], ['dashboard_order_manager_index', 'dashboard_order_manager_new'], 'flaticon-users', false, null, null, 'sidebar.sales.orders.orders_settings.orders_managers', [], 'dashboard_order_manager_index'),
                self::itemSidebar(['ROLE_ORDER_MAIL_SETTING_EDIT'], [], ['dashboard_order_mail_setting_edit'],
                    'flaticon-envelope', false, null, null,
                    'sidebar.contacts.contact_settings.contact_mail_setting', [],
                    'dashboard_order_mail_setting_edit'
                ),
            ], null);

	        (!is_null($settings)) ? $menu['items'][] = $settings : null;
        }

        return $menu;
    }

    private function eCommerceBundleRoles(): array
    {
        return [
            'order' => 'ROLE_ORDER_CREATE_EDIT',
            'product_catogory' => 'ROLE_PRODUCT_CATEGORY_CREATE_EDIT',
            'product' => 'ROLE_PRODUCT_CREATE_EDIT',
            'payment-method' => 'ROLE_PAYMENT_METHODS_CREATE_EDIT',
            'shipping-method' => 'ROLE_SHIPPING_METHODS_CREATE_EDIT',
        ];
    }
}