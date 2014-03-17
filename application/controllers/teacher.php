<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Teacher extends MY_Controller {

	public function index(){

		$teachers = $this->TeacherModel->getAll();

		foreach($teachers as &$teacher){

			$class = $this->ClassModel->getClassByTeacherId($teacher['id']);

			if($class == false){
				$teacher['class'] = '无';
			}else{
				$teacher['class'] = $class['name'];
			}
			$teacherClassFee = $this->AttendanceLogModel->sumTeacherFeeByTeacherName($teacher['name']);
			$teacher['costs'] = $teacherClassFee;
			$teacher['classFee'] = $class['teacher_fee'];
		}

		$classes = $this->ClassModel->getAll();

		$this->setHeader(array("pageName" => "teacher-page"));
		$this->showView("teacher/index",array('teachers'=>$teachers ,'classes'=>$classes));
	}

	public function create(){

		$classes = $this->ClassModel->getAll();

		$this->setHeader(array("pageName" => "teacher-page"));
		$this->showView("teacher/add",array('classes'=>$classes));
	}

	public function single($teacherName = ""){
		if($teacherName == ""){
			show_404();
		}

		$teacherName = urldecode($teacherName);
		$teacher = $this->TeacherModel->getTeacherByName($teacherName);
		
		$classes = $this->MappingTeacherClassModel->getMapByTeacherId($teacher['id']);
		foreach($classes as &$class){
			$_class = $this->ClassModel->getClassById($class['class_id']);
			$class['name'] = $_class['name'];
		}
		$attendances = $this->AttendanceLogModel->getAttendancesByTeacherName($teacher['name']);
		
		$newAttendances = array();
		foreach($attendances as $attendance){
			$date = $attendance['sign_date'];
			$attclass = $attendance['class_name'];
			if(!array_key_exists($date , $newAttendances)){
				$newAttendances[$date] = array();
			}
			if(!array_key_exists($attclass, $newAttendances[$date])){
				$newAttendances[$date][$attclass] = 0;
			}
			$newAttendances[$date][$attclass] += $attendance['teacher_earnings'];
		}
		$data = array(
				'teacher' => $teacher,
				'classes' => $classes,
				'attendances' => $newAttendances
			);
		$this->setHeader(array("pageName" => "teacher-page"));
		$this->showView("teacher/info",$data);
	}

	public function getClassFee(){
		$teacherName = $this->input->get_post('teacherName');
		$className =$this->input->get_post('className');

		$teacherFee = $this->TeacherClassFeeModel->getEarningByTeacherNameAndClassName($teacherName,$className);
		echo json_encode(array('fee'=>$teacherFee));
	}

	public function search(){
		$context = $this->input->get_post('context');
		if($context == ""){
			$teachers = $this->TeacherModel->getAll();
		}else{
			$teachers = $this->TeacherModel->searchTeacherByName($context);
		}
		foreach($teachers as &$teacher){

			$class = $this->ClassModel->getClassByTeacherId($teacher['id']);

			if($class == false){
				$teacher['class'] = '无';
			}else{
				$teacher['class'] = $class['name'];
			}

			$teacherClassFee = $this->AttendanceLogModel->sumTeacherFeeByTeacherName($teacher['name']);
			$teacher['costs'] = $teacherClassFee;

			$classFee = $this->TeacherClassFeeModel->getEarningByTeacherName($teacher['name']);
			if($classFee !== false)
				$teacher['classFee'] = $classFee.'元/次';
			else
				$teacher['classFee'] = '暂无';
		}

		echo json_encode(array('teachers'=>$teachers));
	}

	public function insert(){

		$name = $this->input->get_post('name');
		$phone = $this->input->get_post('phone');
		$address = $this->input->get_post('address');
		$this->TeacherModel->insert(array('name'=>$name,'phone'=>$phone , 'address' => $address ));

		$classId = $this->input->get_post('classId');
		$classFee = $this->input->get_post('classFee');

		if(!$classId || !$classFee){
			echo json_encode(array('className'=> urlencode($name)));
		}else{
			$class = $this->ClassModel->getClassById($classId);
			$classFee = $classFee == ""?0:$classFee;
			$data = array(
						'teacher_name'=>$name , 
						'class_name' => $class['name'] , 
						'earnings' => $classFee,
						'create_date' => date('Y-m-d H:i:s')
					);
			$this->TeacherClassFeeModle->insert($data);
			echo json_encode(array('className'=> urlencode($name)));
		}
	}

	public function deleteTeacher(){

		$id = $this->input->get_post('teacherId');

		$teacher = $this->TeacherModel->getTeacherById($id);

		$this->ClassModel->updateByTeacherId($id,array('teacher_id' => 0));
		$this->MappingTeacherClassModel->deleteByTeacherId($id);
		$this->TeacherModel->deleteById($id);
		$this->AttendanceLogModel->updateAviliableByTeacherName("-1",$teacher['name']);
		echo json_encode(array('success'=>true));
	}

	public function ajaxGetList(){
		$teachers = $this->TeacherModel->getAll();
		echo json_encode(array('result'=> true , 'teachers' => $teachers));
	}

	public function checkName(){
		$name = $this->input->get_post('name');
		$teacher = $this->TeacherModel->getTeacherByName($name);
		if($teacher === false){
			echo json_encode(array('result' => true));
		}else{
			echo json_encode(array('result' => false));
		}
	}

	public function editTeacher(){

		$id = $this->input->get_post('id');
		$phone = $this->input->get_post('phone');
		$address = $this->input->get_post('address');
		#$this->TeacherModel->updateTeacherPhoneById($phone , $id);

		$this->TeacherModel->updateById(array('phone' => $phone , 'address' => $address) , $id);
		echo json_encode(array('result' => true));
	}

	public function ajaxGetReport(){

		$params = $this->input->get();
		list($startTime , $endTime) = array( $params['start_time'] , $params['end_time']);
		
		$teacher = $this->TeacherModel->getTeacherById($params['teacherId']);
		$attendances = $this->AttendanceLogModel->getAttendancesByTeacherName($teacher['name']);
		
		$newAttendances = array();
		foreach($attendances as $attendance){
			$date = $attendance['sign_date'];
			if($date >= $startTime && $date <= $endTime){
				$attclass = $attendance['class_name'];
				if(!array_key_exists($date , $newAttendances)){
					$newAttendances[$date] = array();
				}
				if(!array_key_exists($attclass, $newAttendances[$date])){
					$newAttendances[$date][$attclass] = 0;
				}
				$newAttendances[$date][$attclass] += $attendance['teacher_earnings'];
			}
		}
		$data = array();
		foreach($newAttendances as $date => $class){
			foreach($class as $name => $fee )
			$data[] = array(
					$date,
					$name,
					$fee
				);
		};
		echo json_encode($data);
 	}

}