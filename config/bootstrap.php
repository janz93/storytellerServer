<?php
use \Storyteller\core\Autoloader;

define( 'ROOT_PATH', dirname( dirname( __FILE__ ) ) . '/' );
define( 'APP_PATH', ROOT_PATH . 'app/' );
define( 'CONFIG_PATH', ROOT_PATH . 'config/' );
define( 'CORE_PATH', ROOT_PATH . 'core/' );

require_once 'dbConfig.php';
$autoloader = new Autoloader('Storyteller');
$autoloader->register();
