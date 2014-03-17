<?php

class MappingStudentClassModel extends CI_Model {

	private $_table = "mapping_student_class";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getMapByStudentId($studentId){
        $sql = 'select * from ' . $this->_table . ' where student_id = ?';
        $query = $this->db->query($sql , array($studentId));
        return $query->result_array();
    }

    public function getMapByClassId($classId){
        $sql = 'select * from ' . $this->_table . ' where class_id = ?';
        $query = $this->db->query($sql , array($classId));
        return $query->result_array();
    }

    public function getStudentIdsByClassId($classId){

        $sql = 'select student_id from ' . $this->_table . ' where class_id = ?';
        $query = $this->db->query($sql , array($classId));
        $result = array();
        foreach($query->result_array() as $row){
            $result[] = $row['student_id'];
        }
        return $result;
    }

    public function getStudentsByClassFeeId($id){

        $sql = 'select student_id from ' . $this->_table . ' where class_fee_id = ?';
        $query = $this->db->query($sql , array($id));
        return $query->result_array();
    }

    public function getMapByClassIdAndStudentId($cId , $sId){
        $sql = 'select * from ' . $this->_table . ' where class_id = ? and student_id = ?';
        $query = $this->db->query($sql , array($cId,$sId));
        if($query->num_rows() > 0){
            return $query->row_array();
        }else{
            return false;
        }
    }

    public function getStudentsByClassIdAndStudentId($id){
        $sql = 'select * from ' . $this->_table . ' where class_id = ? and student_id = ?';
        $query = $this->db->query($sql , array($id));
        return $query->result_array();
    }

    public function getClassFeeIdByStudentId($id){

        $sql = 'select class_fee_id from ' . $this->_table . ' where student_id = ?';
        $query = $this->db->query($sql , array($id));
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['class_fee_id'];
        }else{
            return false;
        }
    }

    public function getClassFeeIdByStudentIdAndClassId($sId , $cId){
        $this->db->where('student_id' , $sId);
        $this->db->where('class_id', $cId);
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['class_fee_id'];
        }else{
            return false;
        }
    }

    public function insert($data){
    	
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateMapByStudentId($feeId,$classId, $studentId){
        $data = array('class_id'=>$classId,'class_fee_id' => $feeId);
        $this->db->where('student_id',$studentId);
        $this->db->update($this->_table,$data);
    }

    public function deleteMapByStudentId($studentId){
        $this->db->delete($this->_table, array('student_id' => $studentId)); 
    }

    public function deleteByClassId($classId){
         $this->db->delete($this->_table, array('class_id' => $classId)); 
    }

    public function deleteStudentsByStudentIdsAndClassId($sIds , $classId){

        foreach($sIds as $sid){
            $this->db->where('student_id', $sid);
            $this->db->where('class_id', $classId);
            $this->db->delete($this->_table); 
        }
    }
}