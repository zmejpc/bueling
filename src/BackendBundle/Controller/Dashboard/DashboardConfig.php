<?php

namespace BackendBundle\Controller\Dashboard;

use Symfony\Component\HttpFoundation\Response;
use SeoBundle\Controller\Dashboard\sidebarSeoBundleTrait;
use UserBundle\Controller\Dashboard\sidebarUserBundleTrait;
use NewsBundle\Controller\Dashboard\sidebarNewsBundleTrait;
use Ecommerce\Controller\Dashboard\sidebarEcommerceBundleTrait;
use StaticBundle\Controller\Dashboard\sidebarStaticBundleTrait;
use ContactBundle\Controller\Dashboard\sidebarContactBundleTrait;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DashboardConfig extends \DashboardBundle\Controller\DashboardConfig
{
    /**
     * @return bool
     */
    public function isUseLogo(): bool
    {
        return false;
    }

    /**
     * @return mixed
     */
    public function elementsForHorizontalMenu(): array
    {
        return [];
    }

    use sidebarContactBundleTrait;
    use sidebarNewsBundleTrait;
    use sidebarSeoBundleTrait;
    use sidebarStaticBundleTrait;
    use sidebarUserBundleTrait;
    use sidebarEcommerceBundleTrait;
    use sidebarNewsBundleTrait;

    /**
     * @return Response
     */
    public function renderAsideMenuElements(): Response
    {
        $sidebar = [];

        $ecommerce = self::sidebarEcommerceBundle();
        (!is_null($ecommerce)) ? $sidebar['ecommerce'] = $ecommerce : null;

        $settings = self::headingSidebar('', 'sidebar.configuration.configuration', [
            self::seoBundleRoles()['seo'], self::staticBundleRoles()['static_page']
//            'ROLE_LOG'
        ], []);

        $supportCenter = self::headingSidebar('', 'sidebar.support_center', [
            self::seoBundleRoles()['seo']],[]);
        if (!is_null($supportCenter)) {
            $contacts = self::sidebarContactBundle();
            (!is_null($contacts)) ? $supportCenter['items'][] = $contacts : null;
            $sidebar['supportCenter'] = $supportCenter;
        }

        $news = self::sidebarNewsBundle();
        (!is_null($news)) ? $sidebar['news'] = $news : null;

        if (!is_null($settings)) {
            $seo = self::sidebarSeoBundle();
            (!is_null($seo)) ? $settings['items'][] = $seo : null;
            $static = self::sidebarStaticBundle();
            (!is_null($static)) ? $settings['items'][] = $static : null;
            $user = self::sidebarUserBundle();
            (!is_null($user)) ? $settings['items'][] = $user : null;
                
            $sidebar['settings'] = $settings;
        }


        return $this->render('@Dashboard/templates/' . self::getTemplateNumber() . '/aside/aside_menu/_element.html.twig', [
            'sidebar' => $sidebar,
            'current_uri' => $this->masterRequest->getRequestUri(),
            'current_route' => $this->router->match($this->masterRequest->getRequestUri())['_route']
        ]);
    }

    /**
     * @return bool
     */
    public function isUseRenderTopBar(): bool
    {
        return true;
    }

    public static function getRoles(): array
    {
        return [
            'Главные роли' => [
                'Разработчик' => 'ROLE_DEVELOPER',
                'Директор' => 'ROLE_DIRECTOR',
                'Пользователи' => 'ROLE_USER',
            ]
        ];
    }

    /**
     * @return Response
     */
    public function renderFooterSupportCenter(): Response
    {
        return new Response('');
    }

    /**
     * @return Response
     */
    public function renderFooterCopyRight(): Response
    {
        return new Response('');
    }
}