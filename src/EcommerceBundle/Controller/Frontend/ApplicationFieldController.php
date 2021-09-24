<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ApplicationField;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ApplicationFieldController extends AbstractController
{
    private $breadcrumbsGenerator;
    private $translator;
    private $em;

    public function __construct(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->translator = $translator;
        $this->em = $em;
    }

    public function showAction(string $slug)
    {
        $applicationField = $this->em->getRepository(ApplicationField::class)->getBySlug($slug);

        if (!$applicationField) {
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_show_application_field'][] = [
            'parameters' => [
                'slug' => $applicationField->translate()->getSlug(),
            ],
            'title' => $applicationField->translate()->getTitle(),
        ];

        return $this->render('application_field/show.html.twig', [
            'applicationField' => $applicationField,
            'seo' => $applicationField->getSeo()->getSeoForPage(),
            'breadcrumbs' => $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
