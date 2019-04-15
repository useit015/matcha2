<?php

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';
require_once '../app/models/user.php';
require_once '../app/helpers/database.php';
require_once '../app/helpers/validate.php';
require_once '../app/routes/users.php';

$app->run();
