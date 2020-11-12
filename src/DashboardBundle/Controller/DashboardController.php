<?php

namespace DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use BackendBundle\Controller\Dashboard\DashboardConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class DashboardController extends AbstractController implements DashboardControllerInterface
{
    protected $templateNumber = null;

    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->templateNumber = DashboardConfig::getTemplateNumber();
    }

    /**
     * Главная страница
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('@Backend/templates/' . $this->templateNumber . '/homepage/index.html.twig', [
            'templateNumber' => $this->templateNumber
        ]);
    }
}