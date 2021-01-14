<?php

namespace BackendBundle\Controller\Dashboard;

use Symfony\Contracts\Translation\TranslatorInterface;
use BackendBundle\Form\Type\Dashboard\DocumentType;
use DashboardBundle\Controller\CRUDController;
use Doctrine\ORM\EntityManagerInterface;
use BackendBundle\Entity\Document;
use Twig\Environment;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DocumentController extends CRUDController
{
    public function getHeadTitle(): string
    {
        return 'Документы';
    }

    public function getGrantedRoles(): array
    {
        return [
            'index' => 'ROLE_DIRECTOR', 'new' => 'ROLE_DIRECTOR',
            'edit' => 'ROLE_DIRECTOR', 'delete' => 'ROLE_DIRECTOR',
        ];
    }

    public function getRouteElements(): array
    {
        return [
            'index' => 'dashboard_documents_index', 'new' => 'dashboard_documents_new',
            'edit' => 'dashboard_documents_edit', 'delete' => 'dashboard_documents_delete',
        ];
    }

    public function getCaptionSubjectIcon(): string
    {
        return 'icon-folder';
    }

    public function getFormType(): string
    {
        return DocumentType::class;
    }

    public function getFormElement()
    {
        $data = new Document();

        return $data;
    }

    public function getRepository(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Document::class);

        return $repository;
    }

    public function getListElementsForIndexDashboard(TranslatorInterface $translator): array
    {
        return [
            'translations-title' => $translator->trans('ui.title', [], 'DashboardBundle'),
            'position' => $translator->trans('ui.position', [], 'DashboardBundle'),
            'showOnWebsite' => $translator->trans('ui.show_on_website', [], 'DashboardBundle'),

        ];
    }

    public function getConfigForIndexDashboard(): array
    {
        return [
            'pageLength' => 25,
            'lengthMenu' => '10, 20, 25, 50, 100, 150',
            'order_column' => 'id',
            'order_by' => "desc"
        ];
    }

    public function createDataForList($item, Environment $twig): array
    {
        return [
            'translations-title' => $item->translate()->getTitle(),
            'position' => $item->getPosition(),
            'showOnWebsite' => $twig->render('@Dashboard/default/crud/list/element/_yes_no.html.twig', [
                'element' => $item->getShowOnWebsite()
            ]),
        ];
    }
}
