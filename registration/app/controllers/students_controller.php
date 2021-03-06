<?php

class StudentsController extends AppController
{
	var $name 		 = 'Students';
	var $uses 		 = array('Student', 'Department', 'Account');
	var $stdFormLock = true;
	
	public $states = array(
            'AN' => 'Andaman and Nicobar Islands',
            'AP' => 'Andhra Pradesh',
            'AR' => 'Arunachal Pradesh',
            'AS' => 'Assam',
            'BR' => 'Bihar',
            'CH' => 'Chandigarh',
            'CT' => 'Chattisgarh',
            'DN' => 'Dadra and Nagar Haveli',
            'DD' => 'Daman and Diu',
            'DL' => 'Delhi',
            'GA' => 'Goa',
            'GJ' => 'Gujarat',
            'HR' => 'Harayana',
            'HP' => 'Himachal Pradesh',
            'JK' => 'Jammu and Kashmir',
            'JH' => 'Jharkhand',
            'KA' => 'Karnataka',
            'KL' => 'Kerala',
            'LD' => 'Lakshwadeep',
            'MP' => 'Madhya Pradesh',
            'MH' => 'Maharashtra',
            'MN' => 'Manipur',
            'ML' => 'Meghalaya',
            'MZ' => 'Mizoram',
            'NL' => 'Nagaland',
            'OR' => 'Orissa',
            'PY' => 'Pondicherry',
            'PB' => 'Punjab',
            'RJ' => 'Rajasthan',
            'SK' => 'Sikkim',
            'TN' => 'Tamil Nadu',
            'TR' => 'Tripura',
            'UL' => 'Uttaranchal',
            'UP' => 'Uttar Pradesh',
			'WB' => 'West Bengal',
			'NR' => 'Outside India');

	public $semesterList =  array('2' => 'II (First Year)', '4' => 'IV (Second Year)', '6' => 'VI (Third Year)', '8' => 'VIII (Fourth Year)', '10' => 'X (Fifth Year - Architecture Only)');

	function getDeptList()
	{
		$deptList = array();
		$tmp = $this->Department->findAll();
		foreach ($tmp as $t) {
			$deptList[$t['Department']['department_id']] = $t['Department']['deptName'];
		}

		return $deptList;
	}

	function index($check = 1)
	{
		$this->set('stdFormLock', $this->stdFormLock);
		if ($check == 1)
			$this->set('instructions', 'Please enter your college ID to begin!');
		elseif ($check == 2)
			$this->set('instructions', 'The form could not be unlocked. The service password you entered may be invalid!');
		elseif ($check == 3)
			$this->set('instructions', 'You tried to update your personal details without filling in your account details. Please retry!');
		else
			$this->set('instructions', 'Your college ID was invalid! Please try again:');
	}

	/* For folks who haven't registered before */
	function add($which = 0)
	{
		$this->set('which', $which);
		if (isset($this->data['Student']['collegeid'])) {
			if ($this->data['Student']['password'] != '$mnit-pass$') {
				$this->redirect('/students/add/1');
			} else {
				if ($this->Student->save($this->data))
					$this->redirect('/students/add/3');
				else
					$this->redirect('/students/add/2');
			}
		}
	}
	
	function account()
	{
		/* Check if we are in service mode */
		if ($this->stdFormLock) {
			if (isset($this->data['Student']['password']) && $this->data['Student']['password'] != '$mnit-pass$') {
				$this->redirect('/students/index/2');
				return;
			}
		}
		
		$fields = array(
					array('type' => 'text', 'name' => 'collegeid', 'label' => 'Student ID', 'value' => $this->data['Student']['collegeid']),
					array('type' => 'select', 'name' => 'category', 'label' => 'Category', 'values' => array('genh' => 'General Hosteler', 'gend' => 'General Day Scholar', 'sch' => 'SC Hosteler', 'scd' => 'SC Day Scholar', 'sth' => 'ST Hosteler', 'std' => 'ST Day Scholar', 'obch' => 'OBC Hosteler', 'obcd' => 'OBC Day Scholar', 'dash' => 'DASA Hosteler', 'dasd' => 'DASA Day Scholar')),
					array('type' => 'select', 'name' => 'mode', 'label' => 'Mode of Payment', 'values' => array('DD' => 'Demand Draft', 'CQ' => 'Cheque')),
					array('type' => 'text', 'name' => 'number', 'label' => 'D.D./Cheque No.'),
					array('type' => 'text', 'name' => 'amount', 'label' => 'Amount')
				);
		$this->set('fields', $fields);
		$this->set('sid', $this->data['Student']['collegeid']);
		
		if (isset($this->data['Account']['category'])) {
			if ($this->Account->save($this->data)) {
				$this->set('done', true);
				$this->redirect('/students/update/'.$this->data['Student']['collegeid']);
			} else {
				$this->set('done', false);	
			}
		}
	}

	function batch($which = 0)
	{
		$this->set('which', $which);
		$feilds = array(
			array('type' => 'select', 'name' => 'deptSelect', 'label' => 'Select Department', 'values' => $this->getDeptList(), 'error' => 'Bad Department Selection'),
			array('type' => 'text', 'name' => 'sem', 'label' => 'Semester', 'value' => '2', 'error' => 'Enter 1,2,3,4,5,6,7,8'),
			array('type' => 'password', 'name' => 'password', 'label' => 'Admin Password', 'error' => 'Invalid Password!')
			);
		if($which == 0) {
			$this->set('feilds', $feilds);
			//$this->redirect('/students/batch/1');
			//return;
		}
		else
		if($which == 1) {
			if($this->data['Student']['password']!= '$mnit-pass$')
				$this->flash('Wrong Password!', '/students/batch/0', 3);
			else {
				echo $this->data['Student']['deptSelect'];
				$sortBy = array('fName', 'lName');
				$stdlst = $this->Student->findAll(array('department_id' => $this->data['Student']['deptSelect'], 'semester' => $this->data['Student']['sem']), null, $sortBy);
				$this->set('dept', $this->data['Student']['deptSelect']);
				$feilds = array();
				$i = 0;
				foreach($stdlst as $lst) {
					$feilds[] = array('type'=>'text', 'name'=>'data[Batch]['.$i.'][batch]', 'label' => $lst['Student']['collegeid'].'&nbsp;&nbsp;&nbsp;'.$lst['Student']['fName'].' '.$lst['Student']['lName'], 'value' => $lst['Student']['batch'], 'error' => 'Format: A-3, B-1, CP-3 etc...');
					$feilds[] = array('type' => 'hidden', 'name' => 'data[Batch]['.$i.'][id]', 'value'=>$lst['Student']['id'], 'label' => NULL, 'error' => NULL);
					$i = $i+1;
				}
				$this->set('feilds', $feilds);
			}
		}
		else
		if ($which == 2) {
			//$this->set('dump', $this->data);
			foreach( $this->data['Batch'] as $student ) {
				$this->Student->query('UPDATE students SET batch = "'.$student['batch'].'" WHERE id = '.$student['id']);
			}
			$this->flash('Saving Data...','/students/batch/0', 2);
//			$this->redirect('/students/batch/0');
			
		}
	}

	function update($sid = 0)
	{
		/* Check if we have atleast $sid or Student.collegeid
		 * If both are present, Student.collegeid takes preference
		 */
		if (isset($this->data['Student']['collegeid'])) {
			$sid = $this->data['Student']['collegeid'];
		} elseif ($sid == 0) {
			/* How did this happen?! */
			$this->flash('Oops! System error, please call a sysadmin immediately and report error code 901. Thank you!', '/students/index', 10);
		}
		
		/* We need to check if the student has entered payment details before continuing */
		if ($this->Account->findCount(array('Account.collegeid' => $sid)) < 1) {
			$this->flash('Account details not entered, illegal access!', '/students/index/3', 3);
		}
		
		$mainFields = array(
						array('type' => 'hidden', 'name' => 'id', 'label' => NULL, 'error' => NULL),
						array('type' => 'text', 'name' => 'collegeid', 'label' => 'Student ID',
								'error' => 'Must begin with 0, and should be 5 or 6 digits long', 'disabled' => true),
						array('type' => 'text', 'name' => 'fName', 'label' => 'First Name', 'error' => 'Cannot be empty, Cannot contain numbers'),
						array('type' => 'text', 'name' => 'lName', 'label' => 'Last Name', 'error' => 'Cannot be empty, Cannot contain numbers'),
						array('type' => 'text', 'name' => 'dob', 'label' => 'Date Of Birth (DDMMYYYY)', 'error' => 'Must be of the form DDMMYYYY'),
						array('type' => 'select', 'name' => 'gender', 'label' => 'Gender',
								'values' => array('m' => 'Male', 'f' => 'Female'), 'error' => 'Cannot be empty'),
						// We don't need this in even semesters
						// array('type' => 'password', 'name' => 'password', 'label' => 'New Password (To be used for your MNIT Account)', 'error' => 'Invalid Password!')
							);

		$addFields	 = array(
						array('type' => 'text', 'name' => 'pAddress1', 'label' => 'Permanent Address (Line 1)', 'error' => 'Cannot be empty'),
						array('type' => 'text', 'name' => 'pAddress2', 'label' => 'Permanent Address (Line 2)', 'error' => 'Cannot be empty'),
						array('type' => 'text', 'name' => 'pCity', 'label' => 'Permanent Address (City/Town/Village)', 'error' => 'Cannot be empty'),
						array('type' => 'select', 'name' => 'pState', 'label' => 'Permanent Address (State)', 'error' => 'Cannot be empty', 'values' => $this->states)
							);

		$extraFields = array(
						array('type' => 'select', 'name' => 'marital', 'label' => 'Marital Status',
								'values' => array('u' => 'Unmarried', 'm' => 'Married', 'd' => 'Divorced'), 'error' => 'Cannot be empty'),
						array('type' => 'text', 'name' => 'bloodGroup', 'label' => 'Blood Group', 'error' => NULL),
						array('type' => 'select', 'name' => 'category', 'label' => 'Category', 
								'values' => array('gen' => 'General', 'sc' => 'SC', 'st' => 'ST', 'obc' => 'OBC', 'das' => 'DASA'), 'error' => 'Cannot be empty'),
						array('type' => 'text', 'name' => 'nationality', 'label' => 'Nationality', 'error' => 'Cannot be empty', 'value' => 'Indian'),
						array('type' => 'text', 'name' => 'email', 'label' => 'Alternate Email Address', 'error' => 'Valid Email address required'),
						array('type' => 'select', 'name' => 'department_id', 'label' => 'Department ID', 'error' => 'Cannot be empty', 'values' => $this->getDeptList()),
						array('type' => 'select', 'name' => 'semester', 'label' => 'Semester', 'error' => 'Cannot be empty', 'values' => $this->semesterList),
						array('type' => 'text', 'name' => 'batch', 'label' => 'Batch No', 'error' => NULL)
					);

		$guardianFields = array(
						array('type' => 'text', 'name' => 'fatherName', 'label' => 'Father\'s/Guardian\'s Full Name (As per certificate)', 'error' => 'Cannot be empty, Cannot contain numbers'),
						array('type' => 'text', 'name' => 'motherName', 'label' => 'Mothers Full Name (As per certificate)', 'error' => 'Cannot be empty, Cannot contain numbers'),
						array('type' => 'text', 'name' => 'parentPhone', 'label' => 'Contact Phone', 'error' => 'Not a valid phone number!'),
						array('type' => 'text', 'name' => 'fatherOccupation', 'label' => 'Father\'s Occupation', 'error' => NULL),
						array('type' => 'text', 'name' => 'motherOccupation', 'label' => 'Mother\'s Occupation', 'error' => NULL),
						array('type' => 'text', 'name' => 'lgName', 'label' => 'Local Guardian\'s Name', 'error' => 'Cannot be empty, Cannot contain numbers'),
						array('type' => 'textarea', 'name' => 'lgAddress', 'label' => 'Local Address', 'error' => NULL),
						array('type' => 'text', 'name' => 'lgPhone', 'label' => 'Local Phone', 'error' => NULL)
					);

		if (isset($this->data['Student']['fName'])) {

			if ($this->Student->save($this->data)) {
				$this->set('courseLayout', $this->requestAction('/students/courses', array('return')));
			} else {
				$this->set('mFields', $mainFields);
				$this->set('aFields', $addFields);	
				$this->set('eFields', $extraFields);
				$this->set('gFields', $guardianFields);
			}

		} else {
			if (preg_match('/^[A-Z0-9]{6,10}$/', $sid)) {
				$this->set('mFields', $mainFields);
				$this->set('aFields', $addFields);
				$this->set('eFields', $extraFields);
				$this->set('gFields', $guardianFields);
				if (($this->Student->findCount(array("Student.collegeid" => $sid))) != 0) {
					$res = $this->Student->find(array('collegeid' => $sid));
					$this->data = $this->Student->read(NULL, $res['Student']['id']);
				}
			} else {
				$this->redirect('/students/index/0');
			} 
		}
	}

	function coursemod()
	{
		$this->set('state', 1);
	}
	
	function courses()
	{
		$sid = $this->data['Student']['collegeid'];
		
		/* We have multiple entry points for this portion */
		$mod = false;
		if (isset($this->data['Student']['modForm']) && $this->data['Student']['modForm'])
			$mod = true;
		if (isset($this->data['Student']['password'])) {
			if ($this->data['Student']['password'] != '$mnit-pass$') {
				$this->set('state', 2);
				$this->render('coursemod');
			}
			$tInfo = $this->Student->findByCollegeid($sid);
			if ($tInfo === false) {
				$this->set('state', 3);
				$this->render('coursemod');
			}
			$sem = $tInfo['Student']['semester'];
			$dep = $tInfo['Student']['department_id'];
			$this->data = $tInfo;
			$mod = true;
		} else {
			$sem = $this->data['Student']['semester'];
			$dep = $this->data['Student']['department_id'];
		}

		/* Get default/existing list of courses to display */
		$courseInfo = array();
		$this->set('sem', $sem);
		$studentExists = $this->Student->query("SELECT COUNT(*) FROM courses_students WHERE collegeid = '$sid'");
		
		if ($studentExists[0][0]['COUNT(*)'] != "0") {
			$oldCourses = $this->Student->query("SELECT * FROM courses_students WHERE collegeid = '$sid'");
			if (!$oldCourses) {
				$this->set('error', true);
			}
			foreach ($oldCourses as $oldCourse) {
				$course = $oldCourse['courses_students']['course_id'];
				$courseInfo[] = array($course, json_decode($this->requestAction('/courses/info/'.$course, array('return'))));
			}
			$this->set('courseInfo', $courseInfo);
		} elseif ($sem != '1') {
			$courses = unserialize($this->requestAction("/courses/fetch/$sem-$dep", array('return')));
			foreach ($courses as $course) {
				$courseInfo[] = array($course, json_decode($this->requestAction('/courses/info/'.$course, array('return'))));
			}
			$this->set('courseInfo', $courseInfo);
		} else {
			$this->set('courseInfo', array());
		}
		
		/* Store updated courses on form submission */
		if(isset($this->data['Courses'])) {
			if ($studentExists[0][0]['COUNT(*)'] != "0") {
				$this->Student->query("DELETE FROM courses_students WHERE collegeid = '$sid'");
			}
			foreach ($this->data['Courses'] as $course) {
				if (!empty($course['course_id'])) {
					$cid = $course['course_id'];
					$bgrade = $course['bgrade'];
					if (isset($course['category']) and !empty($course['category']))
						$category = $course['category'];
					else
					 	$category = ($bgrade)?1:0;
					$res = $this->Student->query("SELECT COUNT(*) FROM courses_students WHERE (collegeid = '$sid' AND course_id = '$cid')");
					if ($res[0][0]['COUNT(*)'] != "0") {
						$this->Student->query("DELETE FROM courses_students WHERE collegeid = '$sid'");
						$this->set('error', true);
						$this->render(); exit;
					} else {
						$this->Student->query("INSERT INTO courses_students VALUES('$sid', '$cid', '$bgrade', '$category')");
					}
				}
				if (isset($course['eca']) and !empty($course['eca'])) {
					$this->Student->query("INSERT INTO courses_students VALUES('$sid', '$course[eca]', '0','3')");
				}
			}
			
			if ($mod) {
				$this->data = NULL;
				$this->set('state', 4);
				$this->render('coursemod');
			}
			else
				$this->redirect('/students/done');
		} else {
			if ($mod)
				$this->render('coursemod');
		}
	}

	function done()
	{
	
	}

	function doprint($id)
	{
		$student = $this->Student->findByCollegeid($id);
		if (!$student) {
			$student = $this->Student->findByCollegeid();
			if (!$student)
				$this->set('error', true);
		} else {
			$res = $this->Student->query("SELECT * FROM courses_students WHERE collegeid = '$id'");
			$accQ = $this->Account->query("SELECT MAX(id) FROM accounts WHERE collegeid = '$id'");
			$accN = $this->Account->query("SELECT * FROM accounts WHERE id = ".$accQ[0][0]["MAX(id)"]);
			if (!$res || !$accN) {
				$this->set('error', true);
			} else {
				$tmp = $student['Student'];
				$daName	= $tmp['fName']." ".$tmp['lName'];
				$daDOB	= substr($tmp['dob'], 0, 2)."-".substr($tmp['dob'], 2, 2)."-".substr($tmp['dob'], 4);
				$daAdd	= $tmp['pAddress1'].", ".$tmp['pAddress2'];

				$this->set('sInfo', array(
								'ID' => $tmp['collegeid'],
								'Full Name' => $tmp['fName']." ".$tmp['lName'],
								'Date of Birth' => $daDOB,
								"Father's/Guardian's Name" => $tmp['fatherName'],
								'Address' => $daAdd,
								'City' => $tmp['pCity'],
								'State' => $this->states[$tmp['pState']]));
				
				$cTot = 0;
				$cInfo = array();
				$bInfo = array();
				$ccInfo = array();
				foreach ($res as $r) {
					$cid = $r['courses_students']['course_id'];
					$cin = json_decode($this->requestAction('/courses/info/'.$cid, array('return')));
					$cTot += $cin[1];
					if ($r['courses_students']['category'] != '3') {
						if ($r['courses_students']['bgrade'] == '0')
							$cInfo[$cid] = $cin;
						else
							$bInfo[$cid] = array($cin, $r['courses_students']['bgrade']);
					} else {
						$ccInfo[$cid] = $cin;
					}
				}
				$this->set('cInfo', $cInfo);
				$this->set('ccInfo', $ccInfo);
				$this->set('bInfo', $bInfo);
				$this->set('cTot', $cTot);
				$this->set('aInfo', $accN);
				$this->render('doprint', 'print');
			}
		}
	}

	function sortByName($x, $y)
	{
		if ($x[1] < $y[1])
			return -1;
		else
			return 1;
	}

	function sortByDept($x, $y)
	{
		if ($x[2] < $y[2])
			return -1;
		else
			return 1;
	}
	
	function sortById($x, $y)
	{
		if ($x[0] < $y[0])
			return -1;
		else
			return 1;
	}

	function view()
	{
		$nReg = $this->Student->findCount();
		$nFee = $this->Account->findCount();

		if ($this->data['Student']['course_id'] != "") {
			$cid = $this->data['Student']['course_id'];
			$courseInfo = array($cid, json_decode($this->requestAction('/courses/info/'.$cid, array('return'))));
			if ($courseInfo[1][0] == "") {
				$this->set('invalidCourse', true);

				$dTmp = $this->getDeptList();
				$dTmp['NULL'] = 'NONE';

				$sTmp = $this->semesterList;
				$sTmp['NULL'] = 'NONE';

				$this->set('nReg', $nReg);
				$this->set('nFee', $nFee);
				$this->set('deptList', $dTmp);
				$this->set('semester', $sTmp);
				$this->render();
			} else {
				$res = $this->Student->query("SELECT * FROM courses_students WHERE course_id = '$cid'");
				$stdList = array();
				
				/* Check if this is an institute elective, if yes, show department info too */
				if (substr($cid, 0, 2) == 'IE') {
					$map = $this->getDeptList();
					foreach ($res as $student) {
						$tmp = $this->Student->find(array('collegeid' => $student['courses_students']['collegeid']));
						$stdList[] = array($student['courses_students']['collegeid'],
											$tmp['Student']['fName']." ".$tmp['Student']['lName'],
											$map[$tmp['Student']['department_id']]);
					}
				} else {
					foreach ($res as $student) {
						$tmp = $this->Student->find(array('collegeid' => $student['courses_students']['collegeid']));
						$stdList[] = array($student['courses_students']['collegeid'], $tmp['Student']['fName']." ".$tmp['Student']['lName']);
					}
				}

				/* Sort. In addition, sort by department for institute electives */
				if ($this->data['Student']['sortBy'] == 'id')
					usort($stdList, array($this, 'sortById'));				
				else
					usort($stdList, array($this, 'sortByName'));
				if (substr($cid, 0, 2) == 'IE')
					usort($stdList, array($this, 'sortByDept'));
				
				$this->set('ListGenerated', true);
				$this->set('list', $stdList);
				$this->set('course', $courseInfo);
				$this->set('output', $this->data['Student']['type']);
				$this->render(NULL, 'plain');
			}
		} else {
			if (isset($this->data['Student']['deptid'])) {
				$conditions = array();
				if ($this->data['Student']['deptid'] != 'NULL') {
					$conditions['Student.department_id'] = $this->data['Student']['deptid'];
					$tmp = $this->getDeptList();
					$this->set('department', $tmp[$this->data['Student']['deptid']]);
				}
				if ($this->data['Student']['semester'] != 'NULL') {
					$conditions['Student.semester'] = $this->data['Student']['semester'];
					$this->set('semester', $this->data['Student']['semester']);
				}

				$list = $this->Student->findAll($conditions);
				$stdList = array();
				foreach ($list as $student) {
					$cRes = $this->Student->query("SELECT * FROM courses_students WHERE collegeid = '".$student['Student']['collegeid']."'");
					$cStr = '';
					foreach ($cRes as $res)
						$cStr .= ' '.$res['courses_students']['course_id'];
					$stdList[] = array($student['Student']['collegeid'], $student['Student']['fName']." ".$student['Student']['lName'], $cStr);
				}

				if ($this->data['Student']['sortBy'] == 'id')
					usort($stdList, array($this, 'sortById'));				
				else
					usort($stdList, array($this, 'sortByName'));

				$this->set('ListGenerated', true);
				$this->set('list', $stdList);
				$this->set('output', $this->data['Student']['type']);
				$this->render(NULL, 'plain');
			}
		}	
		
		$dTmp = $this->getDeptList();
		$dTmp['NULL'] = 'NONE';

		$sTmp = $this->semesterList;
		$sTmp['NULL'] = 'NONE';

		$this->set('nReg', $nReg);
		$this->set('nFee', $nFee);
		$this->set('deptList', $dTmp);
		$this->set('semester', $sTmp);
	}

	function getlist()
	{
		$res = $this->Student->findAll();
		$this->set('list', $res);
	}
	
}
