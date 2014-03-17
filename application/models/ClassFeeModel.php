<?php

class ClassFeeModel extends CI_Model {

	private $_table = "class_fee";

    function __construct()
    {
        parent::__construct();
    }

    public function getClassFeeByClassId($classId){
        $sql = 'select * from ' .$this->_table . ' where class_id = ?';
        $query = $this->db->query($sql ,array($classId));
        return $query->result_array();
    }

    public function getClassFeeById($id){
        $sql = 'select * from ' .$this->_table . ' where id = ?';
        $query = $this->db->query($sql ,array($id));
        return $query->row_array();
    }

    public function insert($data){
    	
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateById($id,$data){
        $this->db->where('id',$id);
        $this->db->update($this->_table,$data);
    }

    public function updateClassFeeByClassId($classId , $data){
        $this->db->where('class_id', $classId);
        $this->db->update($this->_table, $data); 
    }

    public function deleteClassFeeById($id){
        $this->db->delete($this->_table, array('id' => $id)); 
    }

    public function deleteByClassId($cId){
        $this->db->delete($this->_table, array('class_id' => $cId)); 
    }
}