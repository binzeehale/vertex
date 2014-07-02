<?php

class AttendanceLogModel extends CI_Model {

	private $_table = "attendance_log";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $query =$this->db->get($this->_table);
        return $query->result_array();
    }

    public function getAllCount(){
        return $this->db->count_all_results($this->_table);
    }

    public function getAttendanceByDataTable($sSearch,$iSortCol,$sSortDir){

        $keyList = array('sign_date','sign_date','class_name',
                            'student_name','teacher_name',
                            'student_cost','user_name','aviliable','teacher_earnings');
        if($sSearch){
            foreach($keyList as $index=>$k){
                if($index == 0){
                    $this->db->like($k,$sSearch);
                }else{
                    $this->db->or_like($k,$sSearch);
                }
            } 
        }
        $this->db->order_by($keyList[$iSortCol], $sSortDir); 
        $query =$this->db->get($this->_table);

        // print $this->db->last_query();
        // exit();
        return $query->result_array();
    }

    public function getAttendanceById($id){
        $sql = 'select * from ' . $this->_table . ' where id=?';
        $query = $this->db->query($sql , array($id));

        return $query->row_array();
    }

    public function getAttendancesByTeacherName($teacherName){
        $sql = 'select * from ' . $this->_table . ' where teacher_name=?  and aviliable !=0 ';
        $query = $this->db->query($sql , array($teacherName));
        return $query->result_array();
    }

    public function getAviliableAttendancesByStudentName($studentName){
        $sql = 'select * from ' . $this->_table . ' where student_name=? and aviliable=1 order by id desc';
        $query = $this->db->query($sql , array($studentName));

        return $query->result_array();
    }

    public function getAviliableAttendancesByStudentNameAndClassName($studentName , $className){
        $sql = 'select * from ' . $this->_table . ' where student_name=? and class_name=? and (aviliable=1 or aviliable=-1)';
        $query = $this->db->query($sql , array($studentName,$className));

        return $query->result_array();
    }

    public function getAttendanceByTimeRange($startTime,$endTime){
        $endTime = date('Y-m-d' , strtotime($endTime) + 24 *3600);
        $sql = 'select * from '.$this->_table.' where sign_date >= ? AND sign_date <= ?';
        $query =$this->db->query($sql ,array($startTime,$endTime));
        return $query->result_array();
    }

    public function sumTeacherFeeByTeacherName($teacherName){

        $currentMonth = date('Y-m-d 00:00:00');
        $nextMonth = date('Y-m-d 00:00:00' , mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));

        $sql = 'select sum(teacher_earnings) as costs from '.$this->_table.' where teacher_name=? AND aviliable = 1 AND sign_date <= ? AND sign_date >= ? group by teacher_name'; 
        $query = $this->db->query($sql , array($teacherName ,$nextMonth , $currentMonth));
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['costs'];
        }else{
            return 0;
        }
    }

    public function getAttendancesByFlag($flag){
        $sql = 'select * from ' . $this->_table . ' where aviliable=?';
        $query = $this->db->query($sql , array($flag));
        return $query->result_array();
    }

    public function insert($data){
    	
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateAviliableById($aviliable , $id){
        $this->db->where('id' , $id);
        $this->db->update($this->_table, array('aviliable' => $aviliable));
    }

    public function updateAviliableByClassName($aviliable , $name){
        $this->db->where('class_name' , $name);
        $this->db->update($this->_table, array('aviliable' => $aviliable));
    }

    public function updateAviliableByStudentName($aviliable , $name){
        $this->db->where('student_name' , $name);
        $this->db->update($this->_table, array('aviliable' => $aviliable));
    }

    public function updateAviliableByTeacherName($aviliable , $name){
        $this->db->where('teacher_name' , $name);
        $this->db->update($this->_table, array('aviliable' => $aviliable));
    }

    public function deleteById($id){
        $this->db->where('id',$id);
        $this->db->delete($this->_table);
    }

    public function deleteByStudentName($sName){
        $this->db->where('student_name',$sName);
        $this->db->delete($this->_table);
    }

    public function deleteByClassName($cName){
        $this->db->where('class_name',$cName);
        $this->db->delete($this->_table);
    }
}