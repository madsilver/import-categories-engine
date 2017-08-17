<?php
/**
 * Created by PhpStorm.
 * User: silver
 * Date: 12/08/17
 * Time: 21:09
 */

require 'vendor/autoload.php';

$args = getopt("f:h:s:");

$engine = new \Silver\Engine($args);
$engine->execute();