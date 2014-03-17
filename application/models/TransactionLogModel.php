<?php

class TransactionLogModel extends CI_Model {

	private $_table = "transaction_log";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getById($id){

        $this->db->where('id',$id);
        $query = $this->db->get($this->_table);
        return $query->row_array();
    }

    public function insert($data){
        
        $this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function getTransactionsByStudentName($studentName){

    	$sql  = 'select * from '.$this->_table.' where student_name =?';
    	$query = $this->db->query($sql , array($studentName));
    	return $query->result_array();
    }

    public function getAttendancesByTimeRange($timeRange){
        $startTime = date('Y-m-d' , strtotime($timeRange['start_time']));
        $endTime = date('Y-m-d' , strtotime($timeRange['end_time']) + 24 *3600);
        $sql = 'select * from '.$this->_table.' where last_update_time >= ? AND last_update_time <= ?';
        $query =$this->db->query($sql ,array($startTime,$endTime));
        return $query->result_array();
    }

    public function deleteByStudentName($sName){
        $this->db->where('student_name',$sName);
        $this->db->delete($this->_table);
    }

    public function deleteById($id){
        $this->db->where('id',$id);
        $this->db->delete($this->_table);
    }
}
