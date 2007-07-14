<?php
class Course extends AppModel {
	var $name='Course';
	
	var $cid;
	var $cname;
	var $deptid;
	var $semester;
	var $credits;
	var $requiresLab;
	var $requiresTutorial;
	var $requiresPresentation;
	var $area;
		
	var $validate=array();

	var $belongsTo = array('Department' =>
						array(	'className' => 'Department',
								'associationForiegnKey' => 'deptid'
							)
					);
	
	var $hasMany=array('Student' =>
						array(	'className' => 'Student',
                            	'dependent' => false,
								'foriegnKey' => 'collegeid'
							)
					);
}
?>
