<?php
/**
 * get ORM(Object relational model class
 *get user class
 *Get functions class
 */

require_once __DIR__."/idiorm.php";
require_once __DIR__."/User.php";
require_once __DIR__."/functions.php";

/**
 * Configure DB with idiorm
 */

$db_host = 'localhost';
$db_name = 'toucan';
$db_user = 'Niall';
$db_pass = '123456';

ORM::configure("mysql:host=$db_host;dbname=$db_name");
ORM::configure("username", $db_user);
ORM::configure("password", $db_pass);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

/**
 * Session Config
 */

session_name('session');
session_start();

/**
 *Settings for confirmation email
 */


$fromEmail = 'ToucanSoft@gmail.com';

if(!$fromEmail){
	// This is only used if you haven't filled an email address in $fromEmail
	$fromEmail = 'noreply@'.$_SERVER['SERVER_NAME'];
}
