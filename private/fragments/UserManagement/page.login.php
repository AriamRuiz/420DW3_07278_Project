<?php
declare(strict_types=1);

use \UserManagement\Service\LoginService;

if (LoginService::isUserLoggedIn()) {
    header("Location: " . WEB_ROOT_DIR);
    http_response_code(302);
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "teacher.standard.css" ?>">
    <script type="text/javascript">
        
        const API_LOGIN_URL = "<?= WEB_ROOT_DIR . "api/login" ?>";
    
    </script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "user-management.page.login.js" ?>" defer></script>
</head>
<body>
<header id="header">
    <?php
    include "standard.page.header.php";
    ?>
</header>
<main id="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">Login</h3>
        </div>
        <form id="loginForm" class="row">
            <?php
            $from = "";
            if (!empty($_REQUEST["from"])) {
                $from = $_REQUEST["from"];
            }
            ?>
            <div class="row justify-content-center">
                <input type="hidden" name="from" value="<?= $from ?>">
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username">
                    <label for="username">Username</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password">
                    <label for="password">Password</label>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <button id="loginButton" type="button" class="btn btn-primary col-12 col-md-4">Log-In!</button>
            </div>
        </form>
    </div>
</main>
<footer id="footer">
    <?php
    include "standard.page.footer.php";
    ?>
</footer>
</body>
</html>