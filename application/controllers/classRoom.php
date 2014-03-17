<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ClassRoom extends MY_Controller {

	public function index(){
		
		$classes = $this->ClassModel->getAll();
		foreach( $classes as &$class){
			if($class['teacher_id'] != 0){
				$teacher = $this->TeacherModel->getTeacherById($class['teacher_id']);
				$class['teacher'] = $teacher['name'];
			}else{
				$class['teacher'] = '';
			}

			$class['studentsCount'] = count($this->MappingStudentClassModel->getMapByClassId($class['id']));
		}

		$data = array(
				'classes' => $classes
			);

		$this->setHeader(array("pageName" => "classroom-page"));
		$this->showView("classroom/index",$data);
	}

	public function create(){

		$teachers = $this->TeacherModel->getAll();

		$this->setHeader(array("pageName" => "classroom-page"));
		$this->showView("classroom/add",array('teachers'=>$teachers));
	}

	public function delete(){

		$id = $this->input->get_post('classId');
		$class = $this->ClassModel->getClassById($id);
		$this->_modifyStudentAccount($id);
		$this->ClassFeeModel->deleteByClassId($id);
		$this->MappingStudentClassModel->deleteByClassId($id);
		$this->MappingTeacherClassModel->deleteByClassId($id);
		$this->TransactionStudentClassModel->deleteByClassId($id);
		//$this->AttendanceLogModel->updateAviliableByClassName('-1' ,$class['name']);
		$this->AttendanceLogModel->deleteByClassName($class['name']);
		$this->ClassModel->deleteClassById($id);

		echo json_encode(array('success' => true));
	}

	private function _modifyStudentAccount($id, $studentIds = array()){

		$maps = array();
		if(empty($studentIds)){
			$maps = $this->MappingStudentClassModel->getMapByClassId($id);
		}else{
			foreach($studentIds as $sId){
				$maps[] = array(
					'class_id'=> $id,
					'student_id'=> $sId,
				);
			} 
		}
		foreach($maps as $map){
			$classIncome = $this->TransactionStudentClassModel->getSumIncomeByClassIdAndStudentId($map['class_id'],$map['student_id']);
			$classCost = $this->TransactionStudentClassModel->getSumCostByClassIdAndStudentId($map['class_id'],$map['student_id']);
			$classBanlance = $classIncome - $classCost;

			$scholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId($map['class_id'],$map['student_id']);
			$scholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId($map['class_id'],$map['student_id']);
			$scholarshipBanlance = $scholarshipIncom - $scholarshipCost;

			if ($scholarshipBanlance != 0){
				$log = array(
					'student_id' => $map['student_id'],
					'class_id' => 0,
					'income' => 0,
					'cost' => $scholarshipCost,
					'type' => 4
				);
				$this->TransactionStudentClassModel->insert($log);
				$log = array(
					'student_id' => $map['student_id'],
					'class_id' => 0,
					'income' => $scholarshipIncom,
					'cost' => 0,
					'type' => 3
				);
				$this->TransactionStudentClassModel->insert($log);
			}

			if($classBanlance != 0){
				$log = array(
					'student_id' => $map['student_id'],
					'class_id' => 0,
					'income' => 0,
					'cost' => $classCost,
					'type' => 2
				);
				$this->TransactionStudentClassModel->insert($log);
				$log = array(
					'student_id' => $map['student_id'],
					'class_id' => 0,
					'income' => $classIncome,
					'cost' => 0,
					'type' => 1
				);
				$this->TransactionStudentClassModel->insert($log);
			}
			$this->TransactionStudentClassModel->deleteByStudentIdandClassId($map['student_id'], $map['class_id']);
		}
	}

	public function single($className){

		if($className == ""){
			show_404();
		}

		$className = urldecode($className);
		$class = $this->ClassModel->getClassByName($className);
		$charges = $this->ClassFeeModel->getClassFeeByClassId($class['id']);

		if($class === false){
			show_404();
		}

		$teacher = $this->TeacherModel->getTeacherById($class['teacher_id']);
		if(count($teacher) == 0 ){
			$teacher['name'] = "无任课老师";
		}
		$class['teacherName'] = $teacher['name'];

		$studentIds = $this->MappingStudentClassModel->getStudentIdsByClassId($class['id']);

		$students = $this->StudentModel->getStudentsByIds($studentIds);
		foreach($students as &$student){
			$cFId = $this->MappingStudentClassModel->getClassFeeIdByStudentIdAndClassId($student['id'],$class['id']);
			$classFee = $this->ClassFeeModel->getClassFeeById($cFId);

			$attendances = $this->AttendanceLogModel->getAviliableAttendancesByStudentNameAndClassName($student['name'],$class['name']);

			$student['grade'] = $classFee['grade'];
			$student['cost'] = $classFee['cost'];
			$student['attendanceCount'] = count($attendances);
		}

		$teachers = $this->TeacherModel->getAll();
		#$teacherFee = $this->TeacherClassFeeModel->getEarningByTeacherNameAndClassName($class['teacherName'],$class['name']);

		$data = array(
				'class' => $class,
				'charges' => $charges,
				'classStudentCount' => count($studentIds),
				'students' => $students,
				'teachers' => $teachers,
				'urlClassName' => urlencode($className)
			);

		$this->setHeader(array("pageName" => "classroom-page"));
		$this->showView("classroom/classinfo",$data);
	}

	public function addStudent($className = "" , $pass = false){

		if($className == ""){
			show_404();
		}

		$className = urldecode($className);
		$class = $this->ClassModel->getClassByName($className);

		$inClassStudentIds = $this->MappingStudentClassModel->getStudentIdsByClassId($class['id']);

		$students = $this->StudentModel->getAll();
		$unselectedStudents = array();

		foreach($students as &$student){
			/*
			$classId = $this->MappingStudentClassModel->getClassIdByStudentId($student['id']);
			$class = $this->ClassModel->getClassById($classId);
			$student['class'] = $class['name'];

			if($class['name'] == $className){
				$student['inClass'] = true;
				$selectedStudents[] = $student;
			}else{
				$student['inClass'] = false;
			}
			*/
			if(in_array($student['id'], $inClassStudentIds)){
				continue;
			}
			$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);
			$student['class'] = "";
			foreach($maps as $m){
				$class = $this->ClassModel->getClassById($m['class_id']);
				$student['class'] .= $class['name'].';';
			}
			$unselectedStudents[] = $student;
		}

		$selectedClass = $this->ClassModel->getClassByName($className);
		$charges = $this->ClassFeeModel->getClassFeeByClassId($selectedClass['id']);

		$data = array( 
			'classId'=>$selectedClass['id'] , 
			'selectedClassName' => $className ,
			'students' => $unselectedStudents, 
			'charges' => $charges,
			'urlClassName' => urlencode($className)
		);

		if($pass !== false){
			$data['passBtn'] = 'true';
		}

		$this->setHeader(array("pageName" => "classroom-page"));
		$this->showView("classroom/addStudent", $data);
	}

	public function search(){
		$context = $this->input->get_post('context');

		$classes = $this->ClassModel->searchClassByName($context);
		foreach($classes as &$class){
			$teacher = $this->TeacherModel->getTeacherById($class['teacher_id']);
			if($teacher != false)
				$class['teacher'] = $teacher['name'];
			else
				$class['teacher'] = '暂无';
		}
		echo json_encode(array('classes' => $classes));
	}

	public function checkClassName(){

		$className = $this->input->get_post('className');
		$result = $this->ClassModel->checkClassName($className);

		echo json_encode(array('success' => true , 'pass' => $result));
	}

	public function insertClass(){
		
		$className = $this->input->get_post('className');
		$teacherId = $this->input->get_post('teacher');
		$teacherFee = $this->input->get_post('teacherFee');

		$charges = $this->input->get_post('charges');
		$charges = json_decode($charges , 'true');

		$classId = $this->ClassModel->insert(array('name' => $className , 'teacher_id' => $teacherId , 'teacher_fee' => $teacherFee));

		$this->MappingTeacherClassModel->insert(array('class_id' => $classId , 'teacher_id' => $teacherId , 'fee' => $teacherFee ,'create_date' => date('Y:m:d H:i:s')));

		foreach($charges as $charge){

			$feeData  = array(
					'class_id' => $classId,
					'grade' => $charge['grade'],
					'cost' => $charge['fee']
				);

			$this->ClassFeeModel->insert($feeData);
		}
		/*
		if($teacherId != 0 && $teacherFee != false){


		
			$teacher = $this->TeacherModel->getTeacherById($teacherId);

			$teacherClassFeeData = array(
					'teacher_name' => $teacher['name'],
					'class_name' => $className,
					'earnings' => $teacherFee,
					'create_date' => date('Y-m-d H:i:s')
				);
			$this->TeacherClassFeeModel->insert($teacherClassFeeData);
		
		}*/
		echo json_encode(array('success' => true , 'className' => urlencode($className) ,'classId' => $classId));
	}

	public function insertStudent(){

		$classId = $this->input->get_post('classId');
		$students = $this->input->get_post('students');
		$feeId = $this->input->get_post('feeId');

		$students = json_decode($students,true);

		foreach($students as $student){
			$data = array(
					'class_id' => $classId,
					'student_id' => $student['id'],
					'class_fee_id' => $feeId
				);
			$this->MappingStudentClassModel->insert($data);
		}

		/*
		$selectedSIds = array();
		foreach($students as $student){
			$selectedSIds[] = $student['id'];
		}
		$studentIds = $this->MappingStudentClassModel->getStudentIdsByClassId($classId);
		foreach ( $studentIds as $id){
			if(!in_array($id, $selectedSIds)){
				$this->MappingStudentClassModel->deleteMapByStudentId($id);
			}
		}

		foreach ( $students as $student){
			$data = array(
					'class_id' => $classId,
					'student_id' => $student['id'],
					'class_fee_id' => $feeId
				);
			$map = $this->MappingStudentClassModel->getMapByClassIdAndStudentId($classId,$student['id']);
			if($map == false ){
				$this->MappingStudentClassModel->insert($data);
			}else{
				if($map['class_fee_id'] != $feeId || $map['class_id'] != $classId)
					$this->MappingStudentClassModel->updateMapByStudentId($feeId,$classId,$student['id']);
			}
		}
		*/
		echo json_encode(array('success'=>true));
	}

	public function editCharges(){

		$classId = $this->input->get_post('classId');
		$charges = $this->input->get_post('charges');
		$charges = json_decode($charges,true);

		$changeIds = array();
		foreach ($charges as $charge){
			if($charge['id'] != 0){
				$changeIds[] = $charge['id'];
			}
		}

		$orgCharges = $this->ClassFeeModel->getClassFeeByClassId($classId);
		foreach( $orgCharges as $orgCharge){
			if(!in_array($orgCharge['id'] , $changeIds)){
				$this->ClassFeeModel->deleteClassFeeById($orgCharge['id']);
			}
		}

		foreach ($charges as $charge){
			$id = $charge['id'];
			$fee = $charge['fee'];
			$grade = $charge['grade'];
			$data = array('class_id' => $classId , 'grade' => $grade , 'cost' => $fee);

			if($id == 0){
				$this->ClassFeeModel->insert($data);
			}else{
				$this->ClassFeeModel->updateById($id , $data);
			}
		}

		echo json_encode(array('success' => true));
	}

	public function editTeacher(){

		$classId = $this->input->get_post('classId');
		$teacherId = $this->input->get_post('teacherId');
		$teacherFee = $this->input->get_post('teacherFee');

		$this->ClassModel->updateByClassId(array('teacher_id' => $teacherId,'teacher_fee' => $teacherFee),$classId);
		$this->MappingTeacherClassModel->updateByClassId(array('teacher_id' => $teacherId,'fee' => $teacherFee),$classId);
		/*
		$teacher = $this->TeacherModel->getTeacherById($teacherId);
		$class = $this->ClassModel->getClassById($classId);

		$data = array(
				'teacherName' => $teacher['name'],
				'earnings' => $teacherFee
			);
		$this->TeacherClassFeeModel->updateByClassName($data, $class['name']);
		*/
		echo json_encode(array('success'=> true));
	}

	public function editClass(){

		$classId = $this->input->get_post('classId');
		$className = $this->input->get_post('className');
		$this->ClassModel->updateNameByClassId($className , $classId);

		echo json_encode(array('success'=> true));
	}

	public function deleteStudents(){
		$classId = $this->input->get_post('classId');
		$studentIds = $this->input->get_post('studentIds');

		/**
		 * Change Log
		 * 2014/3/17
		 * Move the deleted students' balance to the default class.[class id = 0]
		 */
		$this->_modifyStudentAccount($classId, $studentIds);
		$this->MappingStudentClassModel->deleteStudentsByStudentIdsAndClassId($studentIds,$classId);

		echo json_encode(array('success'=> true));
	}

	public function ajaxGetClassCharges(){
		$classId = $this->input->get_post('classId');

		$charges = $this->ClassFeeModel->getClassFeeByClassId($classId);
		echo json_encode(array('charges' => $charges));
	}

	public function ajaxCheckStudentCharges(){

		$id = $this->input->get_post('id');
		$studentIds = $this->MappingStudentClassModel->getStudentsByClassFeeId($id);

		if(count($studentIds) > 0 ){
			echo json_encode(array( 'hasStudent'=> true));
		}else{
			echo json_encode(array( 'hasStudent'=> false));
		}
	}

	public function ajaxGetInClassStudents(){

		$name = $this->input->get_post('className');
		$class = $this->ClassModel->getClassByName($name);
		$sIds = $this->MappingStudentClassModel->getStudentIdsByClassId($class['id']);

		$students = $this->StudentModel->getStudentsByIds($sIds);
		$data = array();
		foreach($students as $student){
			$data[] = array('id' => $student['id'] , 'name' => $student['name']);
		}
		echo json_encode(array('students' => $data));
	}

	public function ajaxGetTeacherInfo(){
		$classId = $this->input->get_post('classId');

		$class = $this->ClassModel->getClassById($classId);

		$data= array(
				'teacherFee' => $class['teacher_fee'],
				'teacherId' => $class['teacher_id']
			);
		echo json_encode($data);
	}

	public function ajaxGetTeacherDetail(){

		$className = $this->input->get_post('className');
		$teacherName = $this->input->get_post('teacherName');

		$earning = $this->TeacherClassFeeModel->getEarningByTeacherNameAndClassName($teacherName,$className);
		$teacher = $this->TeacherModel->getTeacherByName($teacherName);

		$data = array(
				'earning' => $earning,
				'teacherId' => $teacher['id']
			);
		echo json_encode($data);
	}
}