<?php

namespace DashboardBundle\Entity\Repository;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface DashboardRepositoryInterface
{
    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @return mixed
     */
    public function allElementsForIndexDashboard(array $dataTable, array $listElementsForIndex);

    /**
     * @param int $id
     * @return mixed
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id);
}