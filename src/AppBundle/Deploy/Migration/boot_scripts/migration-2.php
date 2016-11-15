<?php

require_once '../../../../../vendor/autoload.php';
require_once realpath('../Migration2.php');

(new \AppBundle\Deploy\Migration\Migration2())->run();