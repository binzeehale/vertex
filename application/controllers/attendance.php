<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends MY_Controller {

    public function index(){

        #$attendances = $this->AttendanceLogModel->getAll();
        // $attendances = $this->AttendanceLogModel->getAttendancesByFlag('1');
        // $_attendances = $this->AttendanceLogModel->getAttendancesByFlag('-1');

        // $attendances = array_merge($attendances,$_attendances);
        $attendances = array();
        $this->setHeader(array("pageName" => "attendance-page"));
        $this->showView("attendance/index" , array('attendances'=>$attendances));
    }

    public function batch(){
        $this->setHeader(array("pageName" => "attendance-page"));
        $this->showView("attendance/batch");
    }

    public function create( $className = "" ){

        $teacher = array();
        $students = array();
        if($className != ""){
            $class = $this->ClassModel->getClassByName(urldecode($className));
            $sIds = $this->MappingStudentClassModel->getStudentIdsByClassId($class['id']);

            foreach($sIds as $sId){
                $student = $this->StudentModel->getStudentById($sId);
                $map = $this->MappingStudentClassModel->getMapByClassIdAndStudentId($class['id'] , $sId);
                $classFee = $this->ClassFeeModel->getClassFeeById($map['class_fee_id']);
                $student['fee'] = $classFee['cost'];

                $students[] = $student;
            }
            $teacher = $this->TeacherModel->getTeacherById($class['teacher_id']);
        }else{
            $class = array('id' => 0);
        }
        if(empty($teacher)){
            $teacher['name'] = '无';
        }

        $classes = $this->ClassModel->getAll();

        $data = array(
                'time' => date('Y-m-d H:i:s'),
                'classId' => $class['id'],
                'teacherName' => $teacher['name'],
                'classes' => $classes,
                'students' => $students
            );
        $this->setHeader(array("pageName" => "attendance-page"));
        $this->showView("attendance/add", $data);

    }

    public function insert(){

        $insertTime = $this->input->get_post('time');
        $classId = $this->input->get_post('classId');
        $teacherName = $this->input->get_post('teacherName');
        $students = $this->input->get_post('students');

        $students = json_decode($students,true);

        $class = $this->ClassModel->getClassById($classId);
        
        //$teacherFee = $this->TeacherClassFee->getEarningByTeacherName($teacherName);

        $userId = $this->getUserId();
        $username = $this->UserModel->getUserNameById($userId);

        foreach($students as $student){

            preg_match('/[-+]?\d*\.?\d*/', $student['cost'], $matches);
            $student['cost'] = $matches[0];

            $data = array(
                    'student_name' => $student['name'],
                    'class_name' => $class['name'],
                    'teacher_name' => $teacherName,
                    'teacher_earnings' => $class['teacher_fee'],
                    'student_cost' => $student['cost'],
                    'sign_date' => $insertTime,
                    'user_name' => $username,
                    'aviliable' => true
                );
            
            $_student = $this->StudentModel->getStudentById($student['id']);


            // $tLog = array(
            //      'student_name' => $_student['name'],
            //      'income' => $student['cost'],
            //      'expenses' => '0',
            //      'type' => '考勤',
            //      'class_name' => $class['name'],
            //      'reason' => $_student['name']."在".$class['name'].'考勤',
            //      'user_name' => $username
            //  );
            // $this->TransactionLogModel->insert($tLog);

            $scholarshipIncom = $this->TransactionStudentClassModel->getScholarshipSumIncomeByClassIdAndStudentId($classId,$student['id']);
            $scholarshipCost = $this->TransactionStudentClassModel->getScholarshipSumCoseByClassIdAndStudentId($classId,$student['id']);
            $scholarshipBanlance = $scholarshipIncom - $scholarshipCost;
            $scholarshipId = 0; $banlanceId = 0;
            if($scholarshipBanlance > 0 && $scholarshipBanlance >= $student['cost']){
                $log = array(
                    'student_id' => $student['id'],
                    'class_id' => $classId,
                    'income' => 0,
                    'cost' => $student['cost'],
                    'type' => 6,
                    'last_update_time' => $insertTime
                );
                $scholarshipId = $this->TransactionStudentClassModel->insert($log);
            }else if ($scholarshipBanlance > 0 && $scholarshipBanlance < $student['cost']){
                $log = array(
                    'student_id' => $student['id'],
                    'class_id' => $classId,
                    'income' => 0,
                    'cost' => $scholarshipBanlance,
                    'type' => 6,
                    'last_update_time' => $insertTime
                );
                $scholarshipId = $this->TransactionStudentClassModel->insert($log);

                $log = array(
                    'student_id' => $student['id'],
                    'class_id' => $classId,
                    'income' => 0,
                    'cost' => $student['cost'] - $scholarshipBanlance,
                    'type' => 5,
                    'last_update_time' => $insertTime
                );
                $banlanceId = $this->TransactionStudentClassModel->insert($log);
            }else{
                $log = array(
                    'student_id' => $student['id'],
                    'class_id' => $classId,
                    'income' => 0,
                    'cost' => $student['cost'],
                    'type' => 5,
                    'last_update_time' => $insertTime
                );
                $banlanceId = $this->TransactionStudentClassModel->insert($log);
            }
            $data['cost_from'] = json_encode(array('sId' => $scholarshipId , 'bId' => $banlanceId));
            $this->AttendanceLogModel->insert($data);
            #$this->StudentModel->updateBalanceById($_student['banlance'] - $student['cost'] , $student['id']);
        }

        echo json_encode(array('success'=>true));
    }

    public function mutiDelete(){

        $attendanceIds = $this->input->get_post('attendanceIds');
        $attendanceIds = json_decode($attendanceIds,true);
        foreach($attendanceIds as $attendanceId){

            $attendance = $this->AttendanceLogModel->getAttendanceById($attendanceId);
            if($attendance['aviliable'] == 1){
                $returnIds = json_decode($attendance['cost_from'],true);
                if($returnIds['bId'] != 0 ){
                    $this->TransactionStudentClassModel->deleteById($returnIds['bId']);
                }
                if($returnIds['sId'] != 0 ){
                    $this->TransactionStudentClassModel->deleteById($returnIds['sId']);
                }
                $this->AttendanceLogModel->deleteById($attendanceId);
            }else{
                $this->AttendanceLogModel->deleteById($attendanceId);
            }
            
        }
        echo json_encode(array('success'=>true));
    }

    public function markAsUnaviliable(){

        $attendaceIds = $this->input->get_post('attendanceIds');
        $attendanceIds = json_decode($attendanceIds,true);

        foreach($attendanceIds as $attendanceId){

            $this->AttendanceLogModel->updateAviliableById(false , $attendanceId);


        }
        echo json_encode(array('success'=>true));
    }


    public function ajaxGetClassesDetail(){
        
        $classes = $this->ClassModel->getAll();
        $result = array(
                array('teacherName' => '' , 'students' => array() , 'classId' => 0)
            );
        $students = array();
        foreach($classes as $class){
            $maps = $this->MappingStudentClassModel->getMapByClassId($class['id']);
            $students = array();
            if(empty($maps))
                $students = array();
            else{
                foreach($maps as $map){
                    $student = $this->StudentModel->getStudentById($map['student_id']);
                    $classFee = $this->ClassFeeModel->getClassFeeById($map['class_fee_id']);
                    
                    $student['fee'] = $classFee['cost'];
                    $students[] = $student;
                }
            }
                
            $teacher = $this->TeacherModel->getTeacherById($class['teacher_id']);

            $result[] = array(
                    'classId' => $class['id'],
                    'students' => $students,
                    'teacherName' =>  isset($teacher['name'])?$teacher['name']:"无"
                );
        }

        echo json_encode($result);
    }

    public function ajaxGetAttendancesByFlag(){

        $flag = $this->input->get_post('flag');
        if($flag == 2){
            $attendances = $this->AttendanceLogModel->getAll();
        }else{
            $attendances = $this->AttendanceLogModel->getAttendancesByFlag($flag);
        }

        $data = array();
        foreach($attendances as &$attendance){

            switch ($attendance['aviliable']) {
                case -1:
                    $attendance['aviliable'] = '过期';
                    break;
                case 0:
                    $attendance['aviliable'] = '无效';
                    break;
                case 1:
                    $attendance['aviliable'] = '有效';
                    break;
            }

            $data[] = array(
                    $attendance['aviliable']=="有效"?'<a href="javascript:void(0)" class="flag"><i class="icon-edit"></i></a><input type="hidden" value="'.$attendance['id'].'">':"#",
                    $attendance['sign_date'] , 
                    $attendance['class_name'] , 
                    $attendance['student_name'],
                    $attendance['teacher_name'],
                    "课费:".$attendance['student_cost']."元/次;课时费:".$attendance['teacher_earnings'],
                    $attendance['user_name'],
                    $attendance['aviliable']
                );
        }
        echo json_encode($data);

    }

    public function ajaxGetAttendances(){

        $timeRange = $this->input->get();
        list($startTime, $endTime) = array($timeRange['start_time'] , $timeRange['end_time']);

        $attendances = $this->AttendanceLogModel->getAttendanceByTimeRange($startTime,$endTime);
        $data = array();
        foreach($attendances as &$attendance){

            switch ($attendance['aviliable']) {
                case -1:
                    $attendance['aviliable'] = '过期';
                    break;
                case 0:
                    $attendance['aviliable'] = '无效';
                    break;
                case 1:
                    $attendance['aviliable'] = '有效';
                    break;
            }

            $data[] = array(
                    '<input type="checkbox" name="attendanceIds" value="'.$attendance['id'].'" />',
                    $attendance['sign_date'] , 
                    $attendance['class_name'] , 
                    $attendance['student_name'],
                    $attendance['teacher_name'],
                    "课费:".$attendance['student_cost']."元/次;课时费:".$attendance['teacher_earnings'],
                    $attendance['user_name'],
                    $attendance['aviliable']
                );
        }
        echo json_encode($data);
    }

    public function deleteAttendance(){

        $id  = $this->input->get_post('id');
        $flag = $this->input->get_post('flag');

        $attendance = $this->AttendanceLogModel->getAttendanceById($id);
        $student = $this->StudentModel->getStudentByName($attendance['student_name']);

        $returnIds = json_decode($attendance['cost_from'],true);
        if($returnIds['bId'] != 0 ){
            $this->TransactionStudentClassModel->deleteById($returnIds['bId']);
        }
        if($returnIds['sId'] != 0 ){
            $this->TransactionStudentClassModel->deleteById($returnIds['sId']);
        }
        $this->AttendanceLogModel->deleteById($id);
        echo json_encode(array('success'=>true));
    }

    public function ajaxGetAttendancesForIndex(){

        $_attendances = array();

        $iDisplayStart = $this->input->get_post('iDisplayStart');
        $iDisplayLength = $this->input->get_post('iDisplayLength');
        $sSearch = $this->input->get_post('sSearch');
        $iSortCol = $this->input->get_post('iSortCol_0');
        $sSortDir = $this->input->get_post('sSortDir_0');

        $attendances = $this->AttendanceLogModel->getAttendanceByDataTable($sSearch,$iSortCol,$sSortDir);
        foreach($attendances as $attendance){
            if($attendance['aviliable'] != 0){
                $_attendances[] = $attendance;
            }
        }
        $attendances = $_attendances;

        $totalCount = $this->AttendanceLogModel->getAllCount();
        $sCount = count($attendances);

        $attendances = array_slice($attendances, $iDisplayStart, $iDisplayLength);
        // $attendances = $this->AttendanceLogModel->getAttendancesByFlag('1');
        // $_attendances = $this->AttendanceLogModel->getAttendancesByFlag('-1');

        // $attendances = array_merge($attendances,$_attendances);
        $data = array();
        foreach($attendances as &$attendance){

            switch ($attendance['aviliable']) {
                case -1:
                    $attendance['aviliable'] = '过期';
                    break;
                case 0:
                    $attendance['aviliable'] = '无效';
                    break;
                case 1:
                    $attendance['aviliable'] = '有效';
                    break;
            }

            $data[] = array(
                    '<input type="checkbox" name="attendanceIds" value="'.$attendance['id'].'" />',
                    $attendance['sign_date'] , 
                    $attendance['class_name'] , 
                    $attendance['student_name'],
                    $attendance['teacher_name'],
                    "课费:".$attendance['student_cost']."元/次;课时费:".$attendance['teacher_earnings'],
                    $attendance['user_name'],
                    $attendance['aviliable']
                );
        }

        echo json_encode(array(
                "sEcho" => $this->input->get_post('sEcho'),
                "iTotalRecords" => $totalCount,
                "iTotalDisplayRecords" => $sCount,
                "aaData" => $data
            ));
    }
}