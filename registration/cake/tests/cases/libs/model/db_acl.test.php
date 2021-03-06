<?php
/* SVN FILE: $Id: db_acl.test.php 5422 2007-07-09 05:23:06Z phpnut $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link				https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package			cake.tests
 * @subpackage		cake.tests.cases.libs.controller.components.dbacl.models
 * @since			CakePHP(tm) v 1.2.0.4206
 * @version			$Revision: 5422 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-07-09 00:23:06 -0500 (Mon, 09 Jul 2007) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
if (!defined('CAKEPHP_UNIT_TEST_EXECUTION')) {
	define('CAKEPHP_UNIT_TEST_EXECUTION', 1);
}

uses('controller'.DS.'components'.DS.'acl', 'model'.DS.'db_acl');

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class AclNodeTestBase extends AclNode {
		var $useDbConfig = 'test_suite';
	}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class AroTest extends AclNodeTestBase {
		var $name = 'AroTest';
		var $useTable = 'aros';
		var $hasAndBelongsToMany = array('AcoTest' => array('with' => 'PermissionTest'));
	}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class AcoTest extends AclNodeTestBase {
		var $name = 'AcoTest';
		var $useTable = 'acos';
		var $hasAndBelongsToMany = array('AroTest' => array('with' => 'PermissionTest'));
	}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class PermissionTest extends CakeTestModel {
		var $name = 'PermissionTest';
		var $useTable = 'aros_acos';
		var $cacheQueries = false;
		var $belongsTo = 'AroTest,AcoTest';
		var $actsAs = null;
	}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class AcoActionTest extends CakeTestModel {
		var $name = 'AcoActionTest';
		var $useTable = 'aco_actions';
		var $belongsTo = 'AcoTest';
	}

/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.cases.libs.controller.components.dbacl.models
 */
	class AclNodeTest extends CakeTestCase {
		var $fixtures = array( 'core.aro', 'core.aco', 'core.aros_aco', 'core.aco_action' );

		function testNodeNesting() {
		}
	}

?>