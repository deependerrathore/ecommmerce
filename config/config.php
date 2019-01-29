<?php

/**
 * Debug come
 */
define('DEBUG',true);

/**
 * Default Controllers
 */
define('DEFAULT_CONTROLLER', 'Home'); // default controller if there isn't any contoller in url
define('ACCESS_RESTRICTED','Restricted'); //controller name for the restricted redirect

/**
 * Project Root - For acutal live server we have to use '/'
 */
define('PROJECT_ROOT','/ecommerce/');

/**
 * Layout and Branding
 */
define('DEFAULT_LAYOUT','default'); // if no layout has ben set in contoller then use this layout
define('SITE_TITLE','eLive'); //this will be used if no site title will be used
define('MENU_BRAND','eLive');

/**
 * Database setting
 */
define('DB_NAME','liveecommerce');
define('DB_USER','root');
define('DB_PASSWORD','root');
define('DB_HOST','127.0.0.1');

/**
 * Cookie and Session details
 */
define('CURRENT_USER_SESSION_NAME','WgF2f6EPltOlryRVc3uvy75ZQLlihme586qbnHE1');//Session name for the logged in user
define('REMEMBER_ME_COOKIE_NAME','TNUwZhpsqipgBaYtK9K5x96JGIEd1KtD1oJxyS9F');//cooke name for logged in user remember me
define('REMEMBER_ME_COOKIE_EXPIRY',604800); // time in seconds for memeber me cookie,expiry 7days

