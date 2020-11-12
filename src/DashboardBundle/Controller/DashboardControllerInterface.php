<?php

namespace DashboardBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface DashboardControllerInterface
{
    /**
     * Главная страница
     * @return Response
     */
    public function indexAction(): Response;
}