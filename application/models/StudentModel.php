<?php

class StudentModel extends CI_Model {

	private $_table = "student";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){

        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getStudentById($id){

        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }

    public function getStudentByName($name){

        $sql = 'select * from ' . $this->_table . ' where name=?';
        $query = $this->db->query($sql,array($name));

        if($query->num_rows() <= 0){
            return false;
        }else{
            return $query->row_array();
        }
    }

    public function getStudentsByIds($ids){

        if(count($ids) == 0 ){
            return array();
        }

        $sql = 'select * from '.$this->_table . ' where id=?';
        if(count($ids) > 1){
            for ( $i = 1; $i<count($ids) ; $i++){
                $sql .= ' or id=?';
            }
        }
        $query = $this->db->query($sql , $ids);
        return $query->result_array();
    }

    public function getArrearsStudents(){
        $sql = 'select * from ' . $this->_table . ' where banlance < 0';
        $query = $this->db->query($sql);

        return $query->result_array();
    }

    public function searchStudentByName($name){
        
        $sql = 'select * from ' . $this->_table . ' where name like "%' . $name . '%"';
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function insert($data){

        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function updateBalanceById($banlance,$id){
        $this->db->where('id' , $id);
        $this->db->update($this->_table, array('banlance' => $banlance));
    }
    public function updateStudentById($data,$id){
        $this->db->where('id' , $id);
        $this->db->update($this->_table, $data);
    }

    public function deleteStudentById($id){

        $this->db->where('id', $id);
        $this->db->delete($this->_table);
    }
}