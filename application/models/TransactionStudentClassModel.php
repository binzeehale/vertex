<?php
class TransactionStudentClassModel extends CI_Model {

	private $_table = "transaction_student_class";

    function __construct()
    {
        parent::__construct();
    }

    public function getAll(){
    	$query = $this->db->get($this->_table);
    	return $query->result_array();
    }

    public function insert($data){
    	$this->db->insert($this->_table,$data);
        return $this->db->insert_id();
    }

    public function getTransactionsByStudentId($id){
        $this->db->where('student_id' , $id);
        $query = $this->db->get($this->_table);
        return $query->result_array();
    }

    public function getSumIncomeByStudentId($studntId){

        $sql = 'select sum(income) as income from ' . $this->_table . ' where student_id=? AND type=1';
        $query = $this->db->query($sql , array($studntId));
        /* BUG. 
            the $query->num_rows() always has one result.
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['income'];
        }else{
            return 0;
        }*/
        $row = $query->row_array();
        return $row['income']?$row['income']:0;
    }

    public function getSumCostByStudentId($studntId){

        $sql = 'select sum(cost) as cost from ' . $this->_table . ' where student_id=? AND ( type = 2 or type = 5 )';
        $query = $this->db->query($sql , array($studntId));
        /*The same bug as below.
        if($query->num_rows() > 0){
            $row = $query->row_array();
            return $row['cost'];
        }else{
            return 0;
        }*/
        $row = $query->row_array();
        return $row['cost']?$row['cost']:0;
    }

    public function getScholarshipSumIncomeByStudentId($studentId){
        $sql = 'select sum(income) as income from ' . $this->_table . ' where student_id=? AND type = 3';
        $query = $this->db->query($sql , array($studentId));
        
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['income'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['income']?$row['income']:0;
    }

    public function getScholarshipSumCostByStudentId($studentId){
        $sql = 'select sum(cost) as cost from ' . $this->_table . ' where student_id=? AND ( type = 4 or type = 6 )';
        $query = $this->db->query($sql , array($studentId));
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['cost'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['cost']?$row['cost']:0;
    }

    public function getSumIncomeByClassIdAndStudentId($classId ,$studentId){

        $sql = 'select sum(income) as income from ' . $this->_table . ' where class_id = ? AND student_id=? AND type=1';
        $query = $this->db->query($sql , array($classId , $studentId));
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['income'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['income']?$row['income']:0;
    }

    public function getSumCostByClassIdAndStudentId($classId ,$studentId){

        $sql = 'select sum(cost) as cost from ' . $this->_table . ' where class_id = ? AND student_id=? AND ( type = 2 or type = 5 )';
        $query = $this->db->query($sql , array($classId ,$studentId));
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['cost'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['cost']?$row['cost']:0;
    }

    public function getScholarshipSumIncomeByClassIdAndStudentId($classId ,$studntId){
        $sql = 'select sum(income) as income from ' . $this->_table . ' where class_id = ? AND student_id=? AND type = 3';
        $query = $this->db->query($sql , array($classId,$studntId));
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['income'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['income']?$row['income']:0;
    }

    public function getScholarshipSumCoseByClassIdAndStudentId($classId ,$studntId){
        $sql = 'select sum(cost) as cost from ' . $this->_table . ' where class_id = ? AND student_id=? AND ( type = 4 or type = 6 )';
        $query = $this->db->query($sql , array($classId,$studntId));
        // if($query->num_rows() > 0){
        //     $row = $query->row_array();
        //     return $row['cost'];
        // }else{
        //     return 0;
        // }
        $row = $query->row_array();
        return $row['cost']?$row['cost']:0;
    }

    public function deleteById($id){
        $this->db->where('id',$id);
        $this->db->delete($this->_table);
    }

    public function deleteByClassId($id){
         $this->db->where('class_id',$id);
        $this->db->delete($this->_table);
    }

    public function deleteByStudentId($id){
        $this->db->where('student_id',$id);
        $this->db->delete($this->_table);
    }

    public function deleteByStudentIdandClassId($sId, $cId){
        $this->db->where('student_id',$sId);
        $this->db->where('class_id',$cId);
        $this->db->delete($this->_table);
    }
}