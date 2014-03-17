<?php

class MappingTeacherClassModel extends CI_Model {

	private $_table = "mapping_teacher_class";

    function __construct()
    {
        parent::__construct();
    }

    public function getMapByTeacherId($tid){
        $this->db->where('teacher_id' , $tid);
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getTeacherNameByClassName($className){

        $sql = 'select * from ' .$this->_table . ' where class_name=?';
        $query =$this->db->query($sql,array($className));
        
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['teacher_name'];
        }else{
            return '';
        }
    }

    public function getEarningByTeacherName($teacherName){

        $sql = 'select * from ' .$this->_table . ' where teacher_name=?';
        $query =$this->db->query($sql,array($teacherName));
        
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['earnings'];
        }else{
            return false;
        }
    }

    public function getEarningByTeacherNameAndClassName($teacherName , $className){
        $sql = 'select * from ' .$this->_table . ' where teacher_name=? AND class_name =?';
        $query =$this->db->query($sql,array($teacherName , $className));
        
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['earnings'];
        }else{
            return 0;
        }
    }

    public function insert($data){
    	
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateByClassName($data , $className){
        $this->db->where('class_name', $className);
        $this->db->update($this->_table, $data); 
    }

    public function updateByClassId($data , $classId){
        $this->db->where('class_id', $classId);
        $this->db->update($this->_table, $data); 
    }

    public function deleteByClassName($className){
        $this->db->where('class_name' , $className);
        $this->db->delete($this->_table);
    }

    public function deleteByClassId($id){
        $this->db->where('class_id' , $id);
        $this->db->delete($this->_table);
    }

    public function deleteByTeacherId($id){
        $this->db->where('teacher_id' , $id);
        $this->db->delete($this->_table);
    }
}