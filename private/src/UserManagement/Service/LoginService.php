<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project LoginService.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-04-06
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace UserManagement\Service;

use Debug;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Exceptions\RuntimeException;
use Teacher\GivenCode\Exceptions\ValidationException;
use UserManagement\DTO\UserDTO;

/**
 * Login Service
 */
class LoginService implements IService {
    
    private UserService $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }
    
    /**
     * Is user logged in
     *
     * @return bool
     */
    public static function isUserLoggedIn() : bool {
        $return_val = false;
        if (!empty($_SESSION["LOGGED_IN_USER"]) && ($_SESSION["LOGGED_IN_USER"] instanceof UserDTO)) {
            $return_val = true;
        }
        Debug::log(("Is logged in user check result: [" . $return_val)
                       ? "true"
                       : ("false" . "]" .
                ($return_val ? (" id# [" . $_SESSION["LOGGED_IN_USER"]->getId() . "].") : ".")));
        return $return_val;
    }
    
    /**
     * Redirects to login page
     *
     * @return void
     */
    public static function redirectToLogin() : void {
        header("Location: " . WEB_ROOT_DIR . "pages/login?from=" . $_SERVER["REQUEST_URI"]);
        http_response_code(303);
        exit();
    }
    
    /**
     * Requires user to be logged in. Redirects to login page otherwise.
     *
     * @return void
     */
    public static function requireLoggedInUser() : void {
        if (!self::isUserLoggedIn()) {
            // not logged in, do a redirection to the login page.
            // Note that I am adding a 'from' URL parameter that will be used to send the user to the right page after login
            self::redirectToLogin();
        }
    }
    
    /**
     * Performs logout
     *
     * @return void
     */
    public function doLogout() : void {
        $_SESSION["LOGGED_IN_USER"] = null;
        Debug::debugToHtmlTable($_SESSION);
    }
    
    /**
     * Performs login and stores logged-in user in session
     *
     * @param string $username
     * @param string $password
     * @return void
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function doLogin(string $username, string $password) : void {
        $user = $this->userService->getByUsername($username);
        if (is_null($user)) {
            throw new ValidationException("Invalid username or password provided.", 404);
        }
        if (!password_verify($password, $user->getPassword())) {
            throw new ValidationException("Invalid username or password provided.", 404);
        }
        $_SESSION["LOGGED_IN_USER"] = $user;
    }
}
