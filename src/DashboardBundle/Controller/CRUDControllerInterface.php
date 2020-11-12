<?php

namespace DashboardBundle\Controller;

use Twig\Environment;
use SeoBundle\Utils\SeoManager;
use UploadBundle\Services\FileHandler;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Utils\DashboardManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CRUDControllerInterface
{
    /**
     * CRUDControllerInterface constructor.
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param DashboardManager $dashboardManager
     * @param AuthorizationCheckerInterface $authChecker
     * @param Environment $twig
     * @param SeoManager $manager
     */
    public function __construct(
        EntityManagerInterface $em, TranslatorInterface $translator, DashboardManager $dashboardManager,
        AuthorizationCheckerInterface $authChecker, Environment $twig, SeoManager $manager, EventDispatcherInterface $eventDispatcher
    );

    /**
     * @return array
     */
    public function getConfigForIndexDashboard(): array;

    /**
     * @return array
     */
    public function getGrantedRoles(): array;

    /**
     * @return array
     */
    public function getRouteElements(): array;

    /**
     * @return mixed
     */
    public function getRepository(EntityManagerInterface $em);

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array;

    /**
     * @return bool
     */
    public function isCustomActionForList(): bool;

    /**
     * @param $item
     * @return array
     */
    public function createDataForList($item, Environment $twig): array;

    /**
     * @param $item
     * @return string
     */
    public function customActionForList($item): string;

    /**
     * @return string
     */
    public function getHeadTitle(): string;

    /**
     * @return array
     */
    public function getPortletHeadIcon(): array;

    /**
     * @return bool
     */
    public function isUseWidgetsForList(): bool;

    /**
     * @return bool
     */
    public function isUseSubTableForList(): bool;

    /**
     * @return bool
     */
    public function isMinimizeAsideMenuElements(): bool;

    /**
     * @return string
     */
    public function renderWidgetsForList(): string;

    public function listAction(Request $request);

    /**
     * @param Request $request
     * @param FileHandler $fileHandler
     * @return mixed
     */
    public function newAction(Request $request, FileHandler $fileHandler);

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInNewAction($object);

    /**
     * @return mixed
     */
    public function getFormElement();

    /**
     * @return string
     */
    public function getFormType(): string;

    /**
     * @param Request $request
     * @param FileHandler $fileHandler
     * @param int $id
     * @return mixed
     */
    public function editAction(Request $request, FileHandler $fileHandler, int $id);

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInEditAction($object);

    /**
     * @return string
     */
    public function getPortletBodyTemplateForForm(): string;

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteAction(int $id);

    /**
     * @param $object
     * @return mixed
     */
    public function customActionInDeleteAction($object);
}