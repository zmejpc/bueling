<?php

namespace DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface DashboardConfigInterface
{
//    /**
//     * @return int
//     */
//    public static function getTemplateNumber(): int;
//
//    /**
//     * @return bool
//     */
//    public function isUseLogo(): bool;
//
//    /**
//     * Логотип в sidebar
//     * @return Response
//     */
//    public function renderLogo(): Response;
//
//    /**
//     * @return bool
//     */
//    public function isUseHorizontalMenu(): bool;
//
//    /**
//     * @return Response
//     */
//    public function renderHorizontalMenu(): Response;
//
//    /**
//     * @return mixed
//     */
//    public function renderHorizontalMenuElements();
//
//    /**
//     * @return mixed
//     */
//    public function elementsForHorizontalMenu(): array;
//
//    /**
//     * @return bool
//     */
//    public function isUseRenderTopBar(): bool;
//
//    /**
//     * @return Response
//     */
//    public function renderTopBar(): Response;
//
//    /**
//     * @return string
//     */
//    public function renderTopBarElements(): string;
//
    /**
     * @return Response
     */
    public function renderAsideMenuElements(): Response;

    /**
     * @return Response
     */
    public function renderFooterSupportCenter(): Response;

    /**
     * @return Response
     */
    public function renderFooterCopyRight(): Response;

    /**
     * @return Response
     */
    public function renderFooter(): Response;

//    /**
//     * @return bool
//     */
//    public function isUseQuickNav(): bool;
//
//    /**
//     * @return Response
//     */
//    public function renderQuickNav(): Response;
//
//    /**
//     * @return bool
//     */
//    public function isUseSidebarToggle(): bool;
//
//    /**
//     * @return Response
//     */
//    public function renderQuickSidebar(): Response;
//
//    /**
//     * @return string
//     */
//    public function renderQuickSidebarElements(): string;
//
//    /**
//     * @param TranslatorInterface $translator
//     * @return array
//     */
//    public static function getRoles(TranslatorInterface $translator): array;
}