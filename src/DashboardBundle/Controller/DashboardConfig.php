<?php

namespace DashboardBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class DashboardConfig extends AbstractController implements DashboardConfigInterface
{
    /**
     * @var EntityManagerInterface|null
     */
    protected $em = null;

    /**
     * @var null|AuthorizationCheckerInterface
     */
    protected $authChecker = null;

    /**
     * @var null|TranslatorInterface
     */
    protected $translator = null;

    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    protected $request = null;

    /**
     * DashboardConfig constructor.
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function __construct(
        RequestStack $requestStack, EntityManagerInterface $em, TranslatorInterface $translator,
        AuthorizationCheckerInterface $authChecker, UrlGeneratorInterface $router
    )
    {
        $this->em = $em;
        $this->translator = $translator;
        $this->authChecker = $authChecker;
        $this->request = $requestStack->getCurrentRequest();
        $this->masterRequest = $requestStack->getMasterRequest();
        $this->router = $router;
    }

    /**
     * @return int
     */
    public static function getTemplateNumber(): int
    {
        return 1;
    }

//    /**
//     * @return bool
//     */
//    public function isUseLogo(): bool
//    {
//        return false;
//    }
//
//    /**
//     * Логотип в sidebar
//     * @return Response
//     */
//    public function renderLogo(): Response
//    {
//        return $this->render('@Dashboard/default/header/_logo.html.twig', [
//            'isUseLogo' => $this->isUseLogo()
//        ]);
//    }
//
//    /**
//     * @return bool
//     */
//    public function isUseHorizontalMenu(): bool
//    {
//        return false;
//    }
//
//    /**
//     * Горизонтальное меню
//     * @return Response
//     */
//    public function renderHorizontalMenu(): Response
//    {
//        return $this->render('@Dashboard/templates/' . self::getTemplateNumber() . '/header/horizontal_menu/_horizontal_menu.html.twig', [
//            'isUseHorizontalMenu' => $this->isUseHorizontalMenu(),
//            'horizontalMenu' => ($this->isUseHorizontalMenu() == true) ? $this->renderHorizontalMenuElements() : null,
//        ]);
//    }
//
//    /**
//     * Горизонтальное меню
//     * @return string
//     */
//    public function renderHorizontalMenuElements(): string
//    {
//        $sidebar = self::elementsForHorizontalMenu();
//
//        return $this->renderView(
//            '@Dashboard/templates/' . self::getTemplateNumber() . '/header/horizontal_menu/_element.html.twig', [
//            'sidebar' => $sidebar
//        ]);
//    }
//
//    /**
//     * @return mixed
//     */
//    public function elementsForHorizontalMenu(): array
//    {
//        return [];
//    }
//
//    /**
//     * @return bool
//     */
//    public function isUseRenderTopBar(): bool
//    {
//        return false;
//    }
//
//    /**
//     * @return Response
//     */
//    public function renderTopBar(): Response
//    {
//        return $this->render('@Dashboard/templates/' . self::getTemplateNumber() . '/header/top_bar/_top_bar.html.twig', [
//            'isUseSidebarToggle' => $this->isUseSidebarToggle(),
//            'elements' => ($this->isUseRenderTopBar() == true) ? $this->renderTopBarElements() : null,
//            'templateNumber' => self::getTemplateNumber()
//        ]);
//    }
//
//    /**
//     * @param string $icon
//     * @param string $title
//     * @param array $elements
//     * @param string $translator
//     * @return array
//     */
//    protected function headingTopBarElement(string $icon, string $title, array $elements, string $translator = 'DashboardBundle'): array
//    {
//        return [
//            'icon' => $icon,
//            'title' => $this->translator->trans($title, [], $translator),
//            'items' => $elements
//        ];
//    }
//
//    /**
//     * @param array $roles
//     * @param string $route
//     * @param string $icon
//     * @param string $title
//     * @param string $translator
//     * @return array|null
//     */
//    protected function elementTopBarElement(
//        array $roles, string $route, string $icon, string $title, string $translator = 'DashboardBundle'
//    )
//    {
//        $isGranted = false;
//
//        foreach ($roles as $role) {
//            if ($this->authChecker->isGranted($role)) {
//                $isGranted = true;
//            }
//        }
//
//        if ($isGranted) {
//            return [
//                'href' => (is_null($route)) ? '' : $this->generateUrl($route),
//                'icon' => $icon,
//                'title' => $this->translator->trans($title, [], $translator)
//            ];
//        } else {
//            return null;
//        }
//    }
//
//    /**
//     * @return string
//     */
//    public function renderTopBarElements(): string
//    {
//        $elements = [];
//
//        return $this->renderView('@Dashboard/templates/' . self::getTemplateNumber() . '/header/top_bar/_elements.html.twig', [
//            'elements' => $elements
//        ]);
//    }
//
    /**
     * @return Response
     */
    public function renderAsideMenuElements(): Response
    {
        $sidebar = [];

        return $this->render('@Dashboard/templates/' . self::getTemplateNumber() . '/aside/aside_menu/_element.html.twig', [
            'sidebar' => $sidebar, 'request' => $this->request
        ]);
    }

    /**
     * @return Response
     */
    public function renderFooterSupportCenter(): Response
    {
        return $this->render('@Dashboard/default/footer/_support_center.html.twig');
    }

    /**
     * @return Response
     */
    public function renderFooterCopyRight(): Response
    {
        return $this->render('@Dashboard/default/footer/_copyright.html.twig');
    }

    /**
     * @return Response
     */
    public function renderFooter(): Response
    {
        return $this->render('@Dashboard/templates/' . self::getTemplateNumber() . '/footer/_footer.html.twig');
    }

//    /**
//     * @return bool
//     */
//    public function isUseQuickNav(): bool
//    {
//        return false;
//    }
//
//    /**
//     * @return Response
//     */
//    public function renderQuickNav(): Response
//    {
//        return $this->render('@Dashboard/default/quick_nav/_quick_nav.html.twig', [
//            'isUseQuickNav' => self::isUseQuickNav(),
//        ]);
//    }
//
//    /**
//     * @return bool
//     */
//    public function isUseSidebarToggle(): bool
//    {
//        return false;
//    }
//
//    /**
//     * @return Response
//     */
//    public function renderQuickSidebar(): Response
//    {
//        return $this->render('@Dashboard/default/quick_sidebar/_quick_sidebar.html.twig', [
//            'isUseSidebarToggle' => $this->isUseSidebarToggle(),
//            'elements' => ($this->isUseSidebarToggle() == true) ? self::renderQuickSidebarElements() : null
//        ]);
//    }
//
//    /**
//     * @return string
//     */
//    public function renderQuickSidebarElements(): string
//    {
//        return $this->renderView('@Dashboard/default/quick_sidebar/_elements.html.twig');
//    }
//
    /**
     * @param null|string $icon
     * @param string $title
     * @param array $roles
     * @param array $items
     * @param string $translator
     * @return array|null
     */
    protected function headingSidebar(
        ?string $icon, string $title, array $roles, array $items, string $translator = 'DashboardBundle'
    ): ?array
    {
        $isGranted = false;

        foreach ($roles as $role) {
            if ($this->authChecker->isGranted($role)) {
                $isGranted = true;
            }
        }

        if ($isGranted) {
            return [
                'isSection' => true,
                'title' => (is_null($title)) ? '' : (is_null($translator)) ? $title : $this->translator->trans($title, [], $translator),
                'icon' => $icon,
                'items' => $items
            ];
        } else {
            return null;
        }
    }

    protected function itemSidebar(
        array $roles, array $editUrl, array $activeRoutes, string $icon,
        bool $isCount, ?int $count, ?string $color, string $title, array $items,
        ?string $route, string $translator = 'DashboardBundle', $useSVG = false, string $svgIcon = ''
    ): ?array
    {
        $isGranted = false;

        foreach ($roles as $role) {
            if ($this->authChecker->isGranted($role)) {
                $isGranted = true;
            }
        }

        if ($isGranted) {
            return [
                'editUrl' => $editUrl,
                'activeRoutes' => $activeRoutes,
                'icon' => $icon,
                'svgIcon' => $svgIcon,
                'useSVG' => $useSVG,
                'isCount' => $isCount,
                'count' => $count,
                'color' => $color,
                'title' => (is_null($title)) ? '' : (is_null($translator)) ? $title : $this->translator->trans($title, [], $translator),
                'url' => (is_null($route)) ? '' : $this->generateUrl($route),
                'items' => $items
            ];
        } else {
            return null;
        }
    }
//
//    /**
//     * @param TranslatorInterface $translator
//     * @return array
//     */
//    public static function getRoles(TranslatorInterface $translator): array
//    {
//        return [];
//    }
}