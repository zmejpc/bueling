<?php

namespace Ecommerce\Controller\Dashboard;

use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarEcommerceBundleTrait
{   
    private function sidebarEcommerceBundle()
    {
    	$menu = self::headingSidebar('', 'E-commerce', [
            self::eCommerceBundleRoles()['product_catogory']
        ], [], 'DashboardBundle');

        if(!is_null($menu)) {
	        $productCategories = self::itemSidebar(['ROLE_PRODUCT_CATEGORY_CREATE_EDIT'], [], ['dashboard_product_category_index', 'dashboard_product_category_new', 'dashboard_product_category_edit'], 'fa fa-warehouse', false, null, null, 'Бренды', [], 'dashboard_product_category_index');

            (!is_null($productCategories)) ? $menu['items'][] = $productCategories : null;

            $products = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product/edit/'], ['dashboard_product_index', 'dashboard_product_new', 'dashboard_product_edit'], 'fa fa-gem', false, null, null, 'sidebar.catalog.products.products', [], 'dashboard_product_index');

            (!is_null($products)) ? $menu['items'][] = $products : null;

            $features = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['product/feature/edit/'], ['dashboard_product_feature_index', 'dashboard_product_feature_new', 'dashboard_product_feature_edit'], 'fa fa-ankh', false, null, null, 'Характеристики', [], 'dashboard_product_feature_index');

            (!is_null($features)) ? $menu['items'][] = $features : null;

            $activityAreas = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['activity-area/edit/'], ['dashboard_activity_area_index', 'dashboard_activity_area_new', 'dashboard_activity_area_edit'], 'fa fa-won-sign', false, null, null, 'Направления', [], 'dashboard_activity_area_index');

            (!is_null($activityAreas)) ? $menu['items'][] = $activityAreas : null;

            $projects = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['project/edit/'], ['dashboard_project_index', 'dashboard_project_new', 'dashboard_project_edit'], 'fa fa-window-restore', false, null, null, 'Проекты', [], 'dashboard_project_index');

            (!is_null($projects)) ? $menu['items'][] = $projects : null;

            $partners = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['partner/edit/'], ['dashboard_partner_index', 'dashboard_partner_new', 'dashboard_partner_edit'], 'fa fa-hands-helping', false, null, null, 'Партнери', [], 'dashboard_partner_index');

            (!is_null($partners)) ? $menu['items'][] = $partners : null;

            $smartLinks = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['smart-link/edit/'], ['dashboard_smart_link_index', 'dashboard_smart_link_new', 'dashboard_smart_link_edit'], 'fa fa-arrow-alt-circle-right', false, null, null, 'Смарт ссылки', [], 'dashboard_smart_link_index');

            (!is_null($smartLinks)) ? $menu['items'][] = $smartLinks : null;

            $applicationFields = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['application-field/edit/'], ['dashboard_application_field_index', 'dashboard_application_field_new', 'dashboard_application_field_edit'], 'fa fa-globe', false, null, null, 'Сферы применения', [], 'dashboard_application_field_index');

            (!is_null($applicationFields)) ? $menu['items'][] = $applicationFields : null;

            $technicType = self::itemSidebar(['ROLE_PRODUCT_CREATE_EDIT'], ['technic-type/edit/'], ['dashboard_application_field_index', 'dashboard_technic_type_new', 'dashboard_technic_type_edit'], 'fa fa-bus', false, null, null, 'Виды техники', [], 'dashboard_technic_type_index');

            (!is_null($technicType)) ? $menu['items'][] = $technicType : null;
        }

        return $menu;
    }

    private function eCommerceBundleRoles(): array
    {
        return [
            'product_catogory' => 'ROLE_PRODUCT_CATEGORY_CREATE_EDIT',
            'product' => 'ROLE_PRODUCT_CREATE_EDIT',
        ];
    }
}