<?php

namespace UserManagement\Service;

use Teacher\GivenCode\Abstracts\IService;

/**
 * User Management Page Navigator Service
 */
class PageNavigator implements IService {
    /**
     * Navigates to login page
     *
     * @return void
     */
    public static function loginPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "UserManagement/page.login.php";
    }
    
    /**
     * Navigates to users page
     *
     * @return void
     */
    public static function usersManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "UserManagement/page.management.users.php";
    }
}