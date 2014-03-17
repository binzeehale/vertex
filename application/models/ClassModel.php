<?php

class ClassModel extends CI_Model {

	private $_table = "class";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getClassById($id){

        $sql = 'select * from ' .$this->_table . ' where id=? limit 1';
        $query = $this->db->query($sql,array($id));
        if($query->num_rows() > 0 ) {
            return $query->row_array();
        }else{
            return false;
        }
    }

    public function getClassByTeacherId($teacherId){

        $sql = 'select * from ' .$this->_table . ' where teacher_id=? limit 1';
        $query = $this->db->query($sql,array($teacherId));
        if($query->num_rows() > 0 ) {
            return $query->row_array();
        }else{
            return false;
        }
    }

    public function getClassByName($name){

        $sql = 'select * from ' .$this->_table . ' where name=? limit 1';
        $query = $this->db->query($sql,array($name));
        if($query->num_rows() > 0 ) {
            return $query->row_array();
        }else{
            return false;
        }
    }

    public function searchClassByName($name){
        
        $sql = 'select * from ' . $this->_table . ' where name like "%' . $name .'%"';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function checkClassName($className){

    	$sql = 'select * from ' .$this->_table . ' where name=? limit 1';
    	$query = $this->db->query($sql,array($className));
        if($query->num_rows() > 0 ) {
    		return false;
    	}else{
    		return true;
    	}
    }

    public function updateNameByClassId($className , $classId){

        $this->db->where('id', $classId);
        $this->db->update($this->_table, array('name' => $className)); 

    }

    public function insert($data){

        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateByClassId($data,$classId){
        $this->db->where('id', $classId);
        $this->db->update($this->_table, $data); 

    }

    public function updateByTeacherId($teacherId,$data){
        $this->db->where('teacher_id', $teacherId);
        $this->db->update($this->_table, $data); 
    }

    public function deleteClassById($classId){
        $this->db->delete($this->_table, array('id' => $classId)); 
    }
}