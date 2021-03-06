<?php
/* SVN FILE: $Id: bootstrap.php 5875 2007-10-23 00:25:51Z phpnut $ */
/**
 * Basic Cake functionality.
 *
 * Core functions for including other source files, loading models and so forth.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 5875 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-10-22 19:25:51 -0500 (Mon, 22 Oct 2007) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
if (!defined('PHP5')) {
	define ('PHP5', (phpversion() >= 5));
}
if (!defined('SERVER_IIS') && php_sapi_name() == 'isapi') {
	define('SERVER_IIS', true);
}
/**
 * Configuration, directory layout and standard libraries
 */
	if (!isset($bootstrap)) {
		require CORE_PATH . 'cake' . DS . 'basics.php';
		$TIME_START = getMicrotime();
		require CORE_PATH . 'cake' . DS . 'config' . DS . 'paths.php';
		require LIBS . 'object.php';
		require LIBS . 'inflector.php';
		require LIBS . 'configure.php';
	}
	require LIBS . 'cache.php';

	Configure::getInstance();

	if(Configure::read('Cache.disable') !== true) {
		$cache = Cache::settings();
		if(empty($cache)) {
			trigger_error('Cache not configured. Please use Cache::config(); in APP/config/core.php', E_USER_WARNING);
			Cache::config('default', array('engine' => 'File'));
		}
	}

	require LIBS . 'session.php';
	require LIBS . 'security.php';
	require LIBS . 'string.php';


	Configure::store(null, 'class.paths');
	Configure::load('class.paths');

	$url = null;
	require CAKE . 'dispatcher.php';
?>