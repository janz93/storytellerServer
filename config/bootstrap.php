<?php
use \Storyteller\core\Autoloader;

define( 'ROOT_PATH', dirname( dirname( __FILE__ ) ) . '/' );
define( 'APP_PATH', ROOT_PATH . 'app/' );
define( 'CONFIG_PATH', ROOT_PATH . 'config/' );
define( 'CORE_PATH', ROOT_PATH . 'core/' );
define('DB_HOST', 'mysql');
define('DB_DATABASE', 'storyteller');
define('DB_USER', 'storyteller');
define('DB_PASS', 'FA12b');

require_once CORE_PATH . 'Autoloader.php';
require 'vendor/autoload.php';

$autoloader = new Autoloader('Storyteller');
$autoloader->register();
