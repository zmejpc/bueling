<?php

namespace UserBundle\Controller\Dashboard;

use Twig\Environment;
use FrontendBundle\Entity\Distribution;
use Doctrine\ORM\EntityManagerInterface;
use DashboardBundle\Controller\CRUDController;
use Symfony\Contracts\Translation\TranslatorInterface;;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class DistributionController extends CRUDController
{
    /**
     * @return array
     */
    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_DIRECTOR', 'new' => 'ROLE_DIRECTOR',
            'edit' => 'ROLE_DIRECTOR', 'delete' => null
        ];
    }

    /**
     * @return array
     */
    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_distribution_index', 'new' => null,
            'edit' => null, 'delete' => null,
        ];
    }

    /**
     * @return string
     */
    public function getHeadTitle(): string
    {
        return
            $this->translator->trans('sidebar.configuration.configuration', [], 'DashboardBundle')
            . ' > Разсылка';
    }

    public function getRepository(EntityManagerInterface $em)
    {
        return $this->em->getRepository(Distribution::class);
    }

    /**
     * @return array
     */
    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'email' => $this->translator->trans('ui.email', [], 'DashboardBundle'),
            'createdAt' => $this->translator->trans('ui.date', [], 'DashboardBundle'),
        ];
    }

    /**
     * @param $item
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createDataForList($item, Environment $twig): array
    {
        return [
            'email' => $item->getEmail(),
            'createdAt' => $item->getCreatedAt()->format('Y.m.d H:i'),
        ];
    }

    public function getFormElement()
    {
        return null;
    }

    public function getFormType(): string
    {
        return '';
    }

    public function getShowActionsColumn()
    {
        return false;
    }
}