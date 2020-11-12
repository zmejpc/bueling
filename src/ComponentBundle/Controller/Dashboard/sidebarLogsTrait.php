<?php

namespace ComponentBundle\Controller\Dashboard;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait sidebarLogsTrait
{
    /**
     * @return array|null
     */
    private function sidebarLogs(): ?array
    {
        return self::itemSidebar(
            ['ROLE_LOG'], [], ['dashboard_log'], 'flaticon-exclamation', false, null, null,
            'sidebar.configuration.log', [], 'dashboard_log'
        );
    }
}