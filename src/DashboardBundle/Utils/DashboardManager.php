<?php

namespace DashboardBundle\Utils;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @author Design studio origami <https://origami.ua>
 */
class DashboardManager implements DashboardManagerInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request|null
     */
    private $request;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * DashboardManager constructor.
     * @param RequestStack $request_stack
     * @param PaginatorInterface $paginator
     */
    public function __construct(RequestStack $request_stack, PaginatorInterface $paginator)
    {
        $this->request = $request_stack->getCurrentRequest();
        $this->paginator = $paginator;
    }

    /**
     * @param $elements
     * @param array $configForIndex
     * @param array $dataTable
     * @return array|mixed
     */
    public function helperForIndexDashboard($elements, array $configForIndex, array $dataTable)
    {
        $sort = !empty($dataTable['sort']['sort']) ? $dataTable['sort']['sort'] : $configForIndex['order_by'];
        $field = !empty($dataTable['sort']['field']) ? $dataTable['sort']['field'] : $configForIndex['order_column'];

        $currentPage = !empty($dataTable['pagination']['page']) ? (int)$dataTable['pagination']['page'] : 1;
        $iDisplayLength = !empty($dataTable['pagination']['perpage']) ? (int)$dataTable['pagination']['perpage'] : -1;

        $countElements = $iDisplayLength < 0 ? $configForIndex['pageLength'] : $iDisplayLength;

        $pagination = $this->paginator->paginate($elements, $currentPage, $countElements, ['wrap-queries']);

        return [
            'meta' => [
                'page' => $currentPage,
                'perPage' => $countElements,
                'sort' => $sort,
                'field' => $field
            ], 'pagination' => $pagination
        ];
    }

    public function getRouteName()
    {
        return $this->request->attributes->get('_route');
    }
}