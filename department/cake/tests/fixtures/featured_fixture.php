<?php
/* SVN FILE: $Id: featured_fixture.php 5811 2007-10-20 06:39:14Z phpnut $ */
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
 * @subpackage		cake.tests.fixtures
 * @since			CakePHP(tm) v 1.2.0.4667
 * @version			$Revision: 5811 $
 * @modifiedby		$LastChangedBy: phpnut $
 * @lastmodified	$Date: 2007-10-20 01:39:14 -0500 (Sat, 20 Oct 2007) $
 * @license			http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */
/**
 * Short description for class.
 *
 * @package		cake.tests
 * @subpackage	cake.tests.fixtures
 */
class FeaturedFixture extends CakeTestFixture {
	var $name = 'Featured';
	var $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary', 'extra'=> 'auto_increment'),
		'article_featured_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => false),
		'published_date' => 'datetime',
		'end_date' => 'datetime',
		'created' => 'datetime',
		'updated' => 'datetime'
	);
	var $records = array(
		array ('id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23', 'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
		array ('id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23', 'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'),
	);
}

?>