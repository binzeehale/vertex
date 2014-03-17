<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Student extends MY_Controller {

	public function index(){

		$students = $this->StudentModel->getAll();

		foreach($students as &$student){
			$student['urlName'] = urlencode($student['name']);
			$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);
			$classes = "";
			foreach($maps as $map){
				$class= $this->ClassModel->getClassById($map['class_id']);
				$classes .= $class['name'].';';
			}
			$student['class'] = $classes != "" ? $classes:"无";
		}

		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/index" , array('students'=>$students));
	}

	public function overage(){

		$students = $this->StudentModel->getAll();
		foreach($students as &$student){
			$scholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByStudentId($student['id']);
			$scholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCostByStudentId($student['id']);
			$student['scholarship'] = $scholarshipIncom - $scholarshipCost;

			$income = $this->TransactionStudentClassModel->getSumIncomeByStudentId($student['id']);
			$cost = $this->TransactionStudentClassModel->getSumCostByStudentId($student['id']);
			$student['banlance'] = $income - $cost;
		}


		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/overage", array('students'=>$students));
	}

	public function create(){

		$classes = $this->ClassModel->getAll();

		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/add",array('classes'=>$classes));
	}

	public function edit(){

		$id = $this->input->post('id');
		if(!$id) show_404();
		$student = $this->StudentModel->getStudentById($id);
		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/edit",array('student'=>$student));

	}

	public function deleteStudent(){

		$studentId = $this->input->get_post('studentId');
		$student = $this->StudentModel->getStudentById($studentId);
		$this->StudentModel->deleteStudentById($studentId);
		$this->MappingStudentClassModel->deleteMapByStudentId($studentId);
		//$this->AttendanceLogModel->updateAviliableByStudentName("-1",$student['name']);
		$this->AttendanceLogModel->deleteByStudentName($student['name']);
		$this->TransactionStudentClassModel->deleteByStudentId($studentId);
		$this->TransactionLogModel->deleteByStudentName($student['name']);
		echo json_encode(array('success'=>true));
	}

	public function single($studentId = 0){

		if(!$studentId){
			show_404();
			return;
		}
		$student = $this->StudentModel->getStudentById($studentId);
		
		if(!$student){
			show_404();
			return;
		}

		$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);
		$classes = "";
		$tClasses = array();
		foreach($maps as $map){
			$class= $this->ClassModel->getClassById($map['class_id']);
			$classFee = $this->ClassFeeModel->getClassFeeById($map['class_fee_id']);
			$classes .= $class['name'].';';

			$classIncome = $this->TransactionStudentClassModel->getSumIncomeByClassIdAndStudentId($map['class_id'],$student['id']);
			$classCost = $this->TransactionStudentClassModel->getSumCostByClassIdAndStudentId($map['class_id'],$student['id']);
			$classBanlance = $classIncome - $classCost;

			$scholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId($map['class_id'],$student['id']);
			$scholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId($map['class_id'],$student['id']);
			$scholarshipBanlance = $scholarshipIncom - $scholarshipCost;
			$tClasses[] = array('id' => $class['id'] ,'name' => $class['name'] ,'classFee'=>$classFee, 'banlance' => $classBanlance , 'scholarship' => $scholarshipBanlance );
		}

		$defaultClassIncome = $this->TransactionStudentClassModel->getSumIncomeByClassIdAndStudentId('0',$student['id']);
		$defaultClassCost = $this->TransactionStudentClassModel->getSumCostByClassIdAndStudentId('0',$student['id']);
		$defaultClassBanlance = $defaultClassIncome - $defaultClassCost;
		$defaultScholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId('0',$student['id']);
		$defaultScholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId('0',$student['id']);	
		$defaultScholarshipBanlance = $defaultScholarshipIncom - $defaultScholarshipCost;
		$tClasses[] = array('id' => 0,'name' => '缺省' ,'classFee'=>array('grade'=>'无','cost'=>'无'), 'banlance' => $defaultClassBanlance , 'scholarship' => $defaultScholarshipBanlance);

		$student['class'] = $classes != "" ? $classes:"无";

		//$transactions = $this->TransactionLogModel->getTransactionsByStudentName($student['name']);
		$transactions = $this->TransactionStudentClassModel->getTransactionsByStudentId($student['id']);
		$history = array('income' => 0 ,'expenses' => 0);
		foreach ($transactions as &$transaction){
			$history['income'] += $transaction['income'];
			$history['expenses'] += $transaction['cost'];

			$class = $this->ClassModel->getClassById($transaction['class_id']);
			$transaction['classname'] = isset($class['name'])?$class['name']:'缺省';
		}

		$attendances = $this->AttendanceLogModel->getAviliableAttendancesByStudentName($student['name']);

		$classes = $this->ClassModel->getAll();

		$data = array(
				'student' => $student,
				'history' => $history,
				'attendances'=>$attendances,
				'transactions'=> $transactions,
				'tClasses' => $tClasses,
				'classes' => $classes
			);

		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/studentInfo",$data);
	}

	public function search(){
		$context = $this->input->get_post('context');

		if($context == ""){
			$students = $this->StudentModel->getAll();
		}else{
			$students = $this->StudentModel->searchStudentByName($context);
		}
		foreach($students as &$student){
			$classId = $this->MappingStudentClassModel->getClassIdByStudentId($student['id']);
			$class = $this->ClassModel->getClassById($classId);
			$student['class'] = isset($class['name'])?$class['name']:'无';
			$student['urlName'] = urlencode($student['name']);
		}
		
		$this->session->set_flashdata('query','context');
		echo json_encode( array('students' => $students));
	}

	public function insert(){

		/*
		var data = {
	          name : name,
	          age : age,
	          sex : sex,
	          school : school,
	          grade : grade,
	          classId : classId,
	          mPhone :　mPhone,
	          fPhone : fPhone,
	          laneline : laneline
	    }
		*/
		$name = $this->input->get_post('name');
		$age = $this->input->get_post('age');
		$sex = $this->input->get_post('sex');
		$school = $this->input->get_post('school');
		$grade = $this->input->get_post('grade');
		$classId = $this->input->get_post('classId');
		$classFeeId = $this->input->get_post('classFeeId');
		$mPhone = $this->input->get_post('mPhone');
		$fPhone = $this->input->get_post('fPhone');
		$laneline = $this->input->get_post('laneline');

		$data = array(
				'name' => $name,
				'age' => $age,
				'sex' => $sex,
				'school' => $school,
				'grade' => $grade,
				'mather_phone' => $mPhone,
				'father_phone' => $fPhone,
				'landline' => $laneline,
				'state' => 'enable',
				'registeration_date' => date('Y-m-d H:i:s')
			);

		$studentId = $this->StudentModel->insert($data);
		if($classId !== 0){
			//$this->MappingStudentClassModel->insert(array('student_id' => $studentId , 'class_id' => $classId ,'class_fee_id' => $classFeeId));
		}
		echo json_encode(array('studentId' => $studentId));
	}

	public function editStudent(){

		$id = $this->input->get_post('id');
		//$name = $this->input->get_post('name');
		$age = $this->input->get_post('age');
		$sex = $this->input->get_post('sex');
		$school = $this->input->get_post('school');
		$grade = $this->input->get_post('grade');
		$classId = $this->input->get_post('classId');
		$classFeeId = $this->input->get_post('classFeeId');
		$mPhone = $this->input->get_post('mPhone');
		$fPhone = $this->input->get_post('fPhone');
		$laneline = $this->input->get_post('laneline');

		$data = array(
				'age' => $age,
				//'name' => $name,
				'sex' => $sex,
				'school' => $school,
				'grade' => $grade,
				'mather_phone' => $mPhone,
				'father_phone' => $fPhone,
				'landline' => $laneline,
				'state' => 'enable'
			);

		$this->StudentModel->updateStudentById($data , $id);
		$student = $this->StudentModel->getStudentById($id);
		echo json_encode(array('studentName' => urlencode($student['name'])));
	}

	public function checkName(){
		$id = $this->input->get_post('id');
		$name = $this->input->get_post('name');
		
		$student = $this->StudentModel->getStudentByName($name);
		if($student !== false){
			echo json_encode(array('result' => false));
		}else{
			echo json_encode(array('result' => true));
		}
	}

	public function ajaxGetAllList(){
		$students = $this->StudentModel->getAll();

		foreach($students as &$student){
			$student['urlName'] = urlencode($student['name']);
			$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);
			$classes = "";
			foreach($maps as $map){
				$class= $this->ClassModel->getClassById($map['class_id']);
				$classes .= $class['name'].';';
			}
			$student['class'] = $classes != "" ? $classes:"无";
		}
		$data = array();
		foreach( $students as $_student ){
			$data[] = array(
				'<a href="javascript:void(0);"><i title="修改" class="icon-edit"></i></a><a href="javascript:void(0);"><i title="删除" class="icon-trash"></i> </a><input type="hidden" value="'.$_student['id'].'" />',
				'<a href="/vertex/student/single/'.$_student['name'].'">'.$_student['name'].'</a>',
				$_student['sex'],
				$_student['school'],
				$_student['grade'],
				$_student['class'],
				$_student['mather_phone'],
				$_student['father_phone'],
				$_student['landline'],
				$_student['banlance']
			);
		}
		echo json_encode($data);
	}

	public function recharge(){
		$id = $this->input->get_post('id');
		$charge = $this->input->get_post('money');
		$classId = $this->input->get_post('classId');
		$time = $this->input->get_post('time');

		$class = $this->ClassModel->getClassById($classId);
		if(!isset($class['name'])) $class['name']='缺省';
		$student = $this->StudentModel->getStudentById($id);
		$tscLog = array(
				'student_id' => $id,
				'class_id' => $classId,
				'income' => $charge,
				'cost' => 0,
				'type' => 1,
				'last_update_time' => $time
			);
		$relateId = $this->TransactionStudentClassModel->insert($tscLog);
		$this->StudentModel->updateBalanceById($charge + $student['banlance'] , $id);

		$userId =$this->getUserId();
		$username =  $this->UserModel->getUserNameById($userId);
		$tLog = array(
				'student_name' => $student['name'],
				'class_name' => $class['name'],
				'income' => $charge,
				'type' => '充值',
				'reason' => $username.'给'.$student['name']. '在班级:' .$class['name'] .'充值'.$charge.'元',
				'relate_id'=>$relateId,
				'user_name' => $username,
				'last_update_time' => $time
			);

		$this->TransactionLogModel->insert($tLog);
		echo json_encode(array('result' => true));
	}

	public function debit(){

		$id = $this->input->get_post('id');
		$charge = $this->input->get_post('money');
		$classId = $this->input->get_post('classId');
		$class = $this->ClassModel->getClassById($classId);
		$time = $this->input->get_post('time');
		if(!isset($class['name'])) $class['name']='缺省';

		$student = $this->StudentModel->getStudentById($id);
		//$this->StudentModel->updateBalanceById($student['banlance'] - $charge, $id);
		$tscLog = array(
				'student_id' => $id,
				'class_id' => $classId,
				'cost' => $charge,
				'income' => 0,
				'type' => 2,
				'last_update_time'=> $time
			);
		$relateId = $this->TransactionStudentClassModel->insert($tscLog);

		$userId =$this->getUserId();
		$username =  $this->UserModel->getUserNameById($userId);
		$tLog = array(
				'student_name' => $student['name'],
				'class_name' => $class['name'],
				'expenses' => $charge,
				'type' => '扣款',
				'relate_id' => $relateId,
				'reason' => $username.'给'.$student['name']. '在班级:' . $class['name'] .'扣款'.$charge.'元',
				'user_name' => $username,
				'last_update_time'=> $time
			);
		$this->TransactionLogModel->insert($tLog);
		echo json_encode(array('result' => true));
	}

	public function arrears(){

		$students = $this->StudentModel->getAll();
		$tClasses = array();
		foreach($students as $student){
			$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);

			/**
			 * Change log 2014/3/17
			 *
			 * Hide the defalute class
			 */

			// if(!$maps){
			// 	$income = $this->TransactionStudentClassModel->getSumIncomeByStudentId($student['id']);
			// 	$cost = $this->TransactionStudentClassModel->getSumCostByStudentId($student['id']);
			// 	$banlance = $income - $cost;
			// 	if($banlance < 0 ){
			// 		$tClasses[] = array('id' => 0, 'class' => array('id'=>0,'name'=>'缺省') , 'student' => $student, 'banlance' => $banlance , 'scholarship' => '' );
			// 	}
			// }

			foreach($maps as $map){
				$class= $this->ClassModel->getClassById($map['class_id']);

				$classIncome = $this->TransactionStudentClassModel->getSumIncomeByClassIdAndStudentId($map['class_id'],$student['id']);
				$classCost = $this->TransactionStudentClassModel->getSumCostByClassIdAndStudentId($map['class_id'],$student['id']);
				$classBanlance = $classIncome - $classCost;

				$scholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId($map['class_id'],$student['id']);
				$scholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId($map['class_id'],$student['id']);
				$scholarshipBanlance = $scholarshipIncom - $scholarshipCost;

				if($classBanlance + $scholarshipBanlance < 0){
					$tClasses[] = array('id' => $class['id'] ,'class' => $class , 'student' => $student, 'banlance' => $classBanlance , 'scholarship' => $scholarshipBanlance );
				}else{
					$classFee = $this->ClassFeeModel->getClassFeeById($map['class_fee_id']);
					if($classBanlance + $scholarshipBanlance - $classFee['cost'] * 3  <= 0 ){
						$tClasses[] = array('id' => $class['id'] ,'class' => $class , 'student' => $student, 'banlance' => $classBanlance , 'scholarship' => $scholarshipBanlance );
					}
				}
			}

			// $defaultClassIncome = $this->TransactionStudentClassModel->getSumIncomeByClassIdAndStudentId('0',$student['id']);
			// $defaultClassCost = $this->TransactionStudentClassModel->getSumCostByClassIdAndStudentId('0',$student['id']);
			// $defaultClassBanlance = $defaultClassIncome - $defaultClassCost;
			// $defaultScholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId('0',$student['id']);
			// $defaultScholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId('0',$student['id']);	
			// $defaultScholarshipBanlance = $defaultScholarshipIncom - $defaultScholarshipCost;
			// if($defaultClassBanlance + $defaultScholarshipBanlance < 0){
			// 	$tClasses[] = array('id' => 0, 'class' => array('id'=>0,'name'=>'缺省') , 'student' => $student , 'banlance' => $defaultClassBanlance , 'scholarship' => $defaultScholarshipBanlance);
			// }
		}
		
		$this->setHeader(array("pageName" => "student-page"));
		$this->showView("student/arrears" , array('tClasses' => $tClasses));
	}

	// public function arrears(){

	// 	$students = $this->StudentModel->getArrearsStudents();
	// 	foreach($students as &$student){
	// 		$student['urlName'] = urlencode($student['name']);
	// 		$maps = $this->MappingStudentClassModel->getMapByStudentId($student['id']);
	// 		$classes = "";
	// 		foreach($maps as $map){
	// 			$class= $this->ClassModel->getClassById($map['class_id']);
	// 			$classes .= $class['name'].';';
	// 		}
	// 		$student['class'] = $classes != "" ? $classes:"无";
	// 	}
		
	// 	$data = array();
	// 	foreach( $students as $student ){
	// 		$data[] = array(
	// 			'<a href="javascript:void(0);"><i title="修改" class="icon-edit"></i></a><a href="javascript:void(0);"><i title="删除" class="icon-trash"></i> </a><input type="hidden" value="'.$student['id'].'" />',
	// 			'<a href="/vertex/student/single/'.$student['name'].'">'.$student['name'].'</a>',
	// 			$student['sex'],
	// 			$student['school'],
	// 			$student['grade'],
	// 			$student['class'],
	// 			$student['mather_phone'],
	// 			$student['father_phone'],
	// 			$student['landline'],
	// 			$student['banlance']
	// 		);
	// 	}
	// 	echo json_encode($data);

	// }

	public function scholarship(){

		$request = $this->input->get();
		list( $id , $classId , $type , $charge , $time ) = array( $request['studentId'] , $request['classId'] , $request['type'] , $request['money'] , $request['time']);

		$student = $this->StudentModel->getStudentById($id);
		$class = $this->ClassModel->getClassById($classId);
		$userId =$this->getUserId();
		$username =  $this->UserModel->getUserNameById($userId);

		switch ($type) {
			case '1':
				$tscLog = array(
						'student_id' => $id,
						'class_id' => $classId,
						'cost' => 0,
						'income' => $charge,
						'type' => 3,
						'last_update_time' => $time
					);
				$logId = $this->TransactionStudentClassModel->insert($tscLog);

				
				$this->TransactionLogModel->insert(array(
						'income'=> 0,
						'expenses'=> 0,
						'type' => '奖学金充值',
						'student_name'=>$student['name'],
						'class_name' => $class['name'],
						'relate_id' => $logId,
						'reason' => $username.'给'.$student['name']. '在班级:' . $class['name'] .'奖学金充值'.$charge.'元',
						'user_name' => $username,
						'last_update_time'=> $time
					));
				break;
			case '2':
				$tscLog = array(
						'student_id' => $id,
						'class_id' => $classId,
						'cost' => $charge,
						'income' => 0,
						'type' => 4,
						'last_update_time' => $time
					);
				$logId = $this->TransactionStudentClassModel->insert($tscLog);
				$this->TransactionLogModel->insert(array(
						'income'=> 0,
						'expenses'=> 0,
						'type' => '奖学金扣款',
						'student_name'=>$student['name'],
						'class_name' => $class['name'],
						'relate_id' => $logId,
						'reason' => $username.'给'.$student['name']. '在班级:' . $class['name'] .'奖学金扣款'.$charge.'元',
						'user_name' => $username,
						'last_update_time'=> $time
					));
				break;
		}
		echo json_encode(array('success' => true ));
	}

	public function shiftMoney(){

		$request = $this->input->post();
		list( $id , $inclassId , $outclassId , $charge ) = array( 
										$request['studentId'] , 
										$request['inclassId'] ,
										$request['outclassId'] ,
										$request['money']
									);

		// $student = $this->StudentModel->getStudentById($id);
		// $inclass = $this->ClassModel->getClassById($inclassId);
		// $outclass = $this->ClassModel->getClassById($outclassId);
		// $userId =$this->getUserId();
		// $username =  $this->UserModel->getUserNameById($userId);

		$inTscLog = array(
				'student_id' => $id,
				'class_id' => $inclassId,
				'cost' => 0,
				'income' => $charge,
				'type' => 1
			);
		$logId = $this->TransactionStudentClassModel->insert($inTscLog);

		$outTtscLog = array(
				'student_id' => $id,
				'class_id' => $outclassId,
				'cost' => $charge,
				'income' => 0,
				'type' => 2
			);
		$logId = $this->TransactionStudentClassModel->insert($outTtscLog);
		echo json_encode(array('success' => true ));
	}

	public function writeoffs(){
		$request = $this->input->post();
		list( $id , $classId ) = array( 
										$request['studentId'] , 
										$request['classId']
									);
		$this->TransactionStudentClassModel->deleteByStudentIdandClassId($id, $classId);
		echo json_encode(array('success' => true ));
	}
}