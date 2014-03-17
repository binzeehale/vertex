<?php

class TeacherModel extends CI_Model {

	private $_table = "teacher";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getTeacherByName($name){
        $sql = 'select * from ' . $this->_table . ' where name =?';
        $query =$this->db->query($sql , array($name));
        if($query->num_rows() > 0){
            return $query->row_array();
        }else{
            return false;
        }
    }

    public function getTeacherById($id){
        $sql = 'select * from ' . $this->_table . ' where id =?';
        $query =$this->db->query($sql , array($id));
        return $query->row_array();
    }

    public function searchTeacherByName($name){
        
        $sql = 'select * from ' . $this->_table . ' where name like "%' . $name .'%"';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function insert($data){
    	
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateById($data , $id){
        $this->db->where('id' , $id);
        $this->db->update($this->_table,$data);
    }

    public function updateTeacherPhoneById($phone , $id){
        $this->db->where('id' , $id);
        $this->db->update($this->_table,array('phone' => $phone));
    }

    public function deleteById($id){

        $this->db->where('id', $id);
        $this->db->delete($this->_table);
    }
}