<?php

require_once '../../../../../vendor/autoload.php';
require_once realpath('../Migration1.php');

(new \AppBundle\Deploy\Migration\Migration1())->run();