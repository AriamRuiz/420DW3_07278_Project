<?php
declare(strict_types=1);

use \UserManagement\Service\LoginService;

?>
<header class="p-3 text-bg-dark">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                </svg>
                <span class="fs-4 p-1">Management</span>
            </a>
            
            <ul class="nav col-12 col-lg-auto me-lg-auto ml-2 mb-2 justify-content-center mb-md-0">
                <li><a href="<?= WEB_ROOT_DIR ?>" class="nav-link px-2 text-white">Home</a></li>
            </ul>
            
            <div class="text-end">
                <?php
                
                if (LoginService::isUserLoggedIn()) {
                    $api_login_url = WEB_ROOT_DIR . "api/login";
                    $method = "delete";
                    echo <<<HTDOC
        <button type="button" class="nav-bar-entry btn btn-outline-light me-2" data-url="$api_login_url" data-method="$method" data-type="api">Logout</button>
HTDOC;
                } else {
                    $login_page_url = WEB_ROOT_DIR . "pages/login";
                    echo <<<HTDOC
        <button type="button" class="nav-bar-entry btn btn-outline-light me-2" data-url="$login_page_url">Login</button>
HTDOC;
                }
                
                ?>
            </div>
        </div>
    </div>
</header>

