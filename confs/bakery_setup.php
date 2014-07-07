<?php 

require '../bakery_config.php';

define('FILE_PERMISSIONS_MODE', 0777);
define('DIRECTORY_PERMISSIONS_MODE', 0777);
define('APP_VERSION_CLI_MINIMUM', '5.5.1');

error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
ini_set('display_errors', 1);
define('C5_EXECUTE', true);


$corePath = CONCRETE_PATH."/concrete";
## Startup check ##	
require($corePath . '/config/base_pre.php');

## Load the base config file ##
require($corePath . '/config/base.php');

## Required Loading
require($corePath . '/startup/required.php');

## Setup timezone support
require($corePath . '/startup/timezone.php'); // must be included before any date related functions are called (php 5.3 +)

## First we ensure that dispatcher is not being called directly
require($corePath . '/startup/file_access_check.php');

require($corePath . '/startup/localization.php');

## Security helpers
require($corePath . '/startup/security.php');

## Autoload core classes
spl_autoload_register(array('Loader', 'autoloadCore'), true);

## Load the database ##
Loader::database();

require($corePath . '/startup/autoload.php');

## Exception handler
require($corePath . '/startup/exceptions.php');

## Set default permissions for new files and directories ##
require($corePath . '/startup/file_permission_config.php');

## Startup check, install ##	
require($corePath . '/startup/magic_quotes_gpc_check.php');

## Default routes for various content items ##
require($corePath . '/config/theme_paths.php');

## Startup check ##	
require($corePath . '/startup/encoding_check.php');

require(CONCRETE_PATH . '/config/site.php');

//Because the site php is not terminated
// TODO: Better way to do this ?!
?>

<?php

// TODO: Remove this if its not needed
$config = array(
	'db-server'		=> DB_SERVER,
	'db-username'	=> DB_USERNAME,
	'db-password'	=> DB_PASSWORD,
	'db-database'	=> DB_DATABASE);
//Set database
Loader::db($config['db-server'], $config['db-username'], $config['db-password'], $config['db-database']);

?>