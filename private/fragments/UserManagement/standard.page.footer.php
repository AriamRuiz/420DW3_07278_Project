<?php
declare(strict_types=1);

?>
<div class="container">
    <div class="flex-column justify-content-start">
        <div class="row links-container">
            <div class="flex-column links-block">
                <a class="d-flex col-12" href="<?= WEB_ROOT_DIR ?>">Home</a>
            </div>
        </div>
        <div class="row copyright-container">
            <div class="flex-column">
                <span class="copyright-notice">Copyright (c) <?= (new DateTime())->format('Y')?> Ariam Ruiz - All rights reserved.</span>
            </div>
        </div>
    </div>
</div>
