<?php

use Slim\App;

return function (App $app) {
    $app->add(\LogApiMW::class . ':RegisterLog');
};
