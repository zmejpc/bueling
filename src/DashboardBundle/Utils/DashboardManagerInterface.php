<?php

namespace DashboardBundle\Utils;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface DashboardManagerInterface
{
    /**
     * DashboardManagerInterface constructor.
     * @param RequestStack $request_stack
     * @param PaginatorInterface $paginator
     */
    public function __construct(RequestStack $request_stack, PaginatorInterface $paginator);

    /**
     * @param $elements
     * @param array $configForIndex
     * @param array $dataTable
     * @return mixed
     */
    public function helperForIndexDashboard($elements, array $configForIndex, array $dataTable);
}