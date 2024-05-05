<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project InternalRouter.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace UserManagement\Service;

use Teacher\GivenCode\Abstracts\IService;
use Teacher\GivenCode\Domain\AbstractRoute;
use Teacher\GivenCode\Domain\APIRoute;
use Teacher\GivenCode\Domain\CallableRoute;
use Teacher\GivenCode\Domain\RouteCollection;
use Teacher\GivenCode\Domain\WebpageRoute;
use Teacher\GivenCode\Exceptions\RequestException;
use Teacher\GivenCode\Exceptions\ValidationException;
use UserManagement\Controller\LoginController;
use UserManagement\Controller\UserController;

/**
 * Internal Router Service
 */
class InternalRouter implements IService {
    private string $uriBaseDirectory;
    protected RouteCollection $routes;
    
    /**
     * @param string $uri_base_directory
     * @throws ValidationException
     */
    public function __construct(string $uri_base_directory = "") {
        $this->uriBaseDirectory = $uri_base_directory;
        $this->routes = new RouteCollection();
        
        $this->routes->addRoute(new APIRoute("/api/login", LoginController::class));
        $this->routes->addRoute(new APIRoute('/api/users', UserController::class));
        $this->routes->addRoute(new WebpageRoute("/index.php", "UserManagement/index_page.php"));
        $this->routes->addRoute(new WebpageRoute("/", "UserManagement/index_page.php"));
        $this->routes->addRoute(new CallableRoute("/pages/login", [PageNavigator::class, "loginPage"]));
        $this->routes->addRoute(new CallableRoute('/pages/users', [PageNavigator::class, 'usersManagementPage']));
    }
    
    /**
     * Routes the application
     *
     * @return void
     * @throws RequestException
     */
    public function route() : void {
        $path = REQUEST_PATH;
        $route = $this->routes->match($path);
        
        if (is_null($route)) {
            // route not found
            throw new RequestException("Route [$path] not found.", 404);
        }
        
        $route->route();
    }
    
    /**
     * Adds an {@see AbstractRoute internal route definition} to the {@see InternalRouter}'s {@see RouteCollection}.
     *
     * @param AbstractRoute $route The route definition to add to the route collection.
     * @return void
     * @throws ValidationException
     */
    public function addRoute(AbstractRoute $route) : void {
        $this->routes->addRoute($route);
    }
}