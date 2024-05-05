<?php
declare(strict_types=1);

require_once __DIR__ . "/../../../private/helpers/init.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Example Page</title>
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "bootstrap.min.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "teacher.standard.css" ?>">
    <link rel="stylesheet" href="<?= WEB_CSS_DIR . "examples.css" ?>">
    <script type="text/javascript" src="<?= WEB_JS_DIR . "jquery-3.7.1.min.js" ?>" defer></script>
    <script type="text/javascript" src="<?= WEB_JS_DIR . "teacher.standard.js" ?>" defer></script>
</head>
<body>
<header id="header" class="header">
    <?php
    include PRJ_FRAGMENTS_DIR . "UserManagement" . DIRECTORY_SEPARATOR . "standard.page.header.php";
    ?>
</header>
<main id="main" class="main">
    <div class="container">
        <div class="row justify-content-center">
            <h3 class="fullwidth text-center">User Management</h3>
        </div>
        <div class="row justify-content-center">
        
        </div>
        <div class="row">
        
        </div>
        <div class="error-display hidden">
            <h1 id="error-class" class="col-12 error-text"></h1>
            <h3 id="error-message" class="col-12 error-text"></h3>
            <div id="error-previous" class="col-12"></div>
            <pre id="error-stacktrace" class="col-12"></pre>
        </div>
    </div>
    <br/>
    <div class="container">
    </div>
</main>
<footer>
    <?php
    include PRJ_FRAGMENTS_DIR . "UserManagement" . DIRECTORY_SEPARATOR . "standard.page.footer.php";
    ?>
</footer>
</body>
</html>
