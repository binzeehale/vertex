<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends MY_Controller {

	public function index(){
		
		$finances = $this->TransactionLogModel->getAll();

		$this->setHeader(array("pageName" => "finance-page"));
		$this->showView("finance/index" , array('finances'=>$finances));
	}

	public function create(){

		$this->setHeader(array("pageName" => "finance-page"));
		$this->showView("finance/add", array('finances' ));
	}

	public function report(){

		$this->setHeader(array("pageName" => "finance-page"));
		$this->showView("finance/report" );
	}

	public function insert(){


		$bigType = $this->input->get_post('bigType');
		$charge = $this->input->get_post("charge");
		$remark = $this->input->get_post('remark');
		if($bigType == 1){
			$income = $charge;
			$expenses = 0;
		}else{
			$income = 0 ;
			$expenses = $charge;
		}

		$userId =$this->getUserId();
		$username = $this->UserModel->getUserNameById($userId);
		$data = array(
				'income' => $income,
				'expenses' => $expenses,
				'type' => '其他',
				'reason' => $remark,
				'user_name' => $username
			);
		$this->TransactionLogModel->insert($data);

		echo json_encode(array('success' => true));
 	}

 	public function delete(){

 		$id = $this->input->get_post('id');
 		$log = $this->TransactionLogModel->getById($id);
 		if(empty($log)){
 			echo json_encode(array('action'=>false,'data'=>'invalid id'));
 		}else{
 			
 			if(isset($log['relate_id']) && $log['relate_id'] != 0 )
 				$this->TransactionStudentClassModel->deleteById($log['relate_id']);
 			$this->TransactionLogModel->deleteById($id);
 			echo  json_encode(array('action'=>true,'data'=>''));
 		}
 	}

 	public function ajaxGetReport(){

		$timeRange = $this->input->get();
		#list($startTime , $endTime) = array( $timeRange['start_time'] , $timeRange['end_time']);
		if($timeRange){
			$transactions = $this->TransactionLogModel->getAttendancesByTimeRange($timeRange);
		}else{
			$transactions = array();
		}

		$data = array(
			'all'=>array() , 
			'expenses' => array() , 
			'income' => array() , 
			'totalIncome' => 0 , 
			'totalExpenses'=> 0
		);
		foreach ($transactions as $transaction){

			$data['all'][] = array(
						date('Y-m-d',strtotime($transaction['last_update_time'])),
						$transaction['student_name'],
						$transaction['class_name'],
						$transaction['income'],
						$transaction['expenses'],
						$transaction['type'],
						$transaction['reason']
					);
			if($transaction['expenses'] > 0){
				$data['expenses'][] = array(
						date('Y-m-d',strtotime($transaction['last_update_time'])),
						$transaction['student_name'],
						$transaction['class_name'],
						$transaction['income'],
						$transaction['expenses'],
						$transaction['type'],
						$transaction['reason']
					);
				$data['totalExpenses'] += $transaction['expenses'];
			}else{
				$data['income'][] = array(
						date('Y-m-d',strtotime($transaction['last_update_time'])),
						$transaction['student_name'],
						$transaction['class_name'],
						$transaction['income'],
						$transaction['expenses'],
						$transaction['type'],
						$transaction['reason']
					);
				$data['totalIncome'] += $transaction['income'];
			}
		}
		echo json_encode($data);
 	}
}
