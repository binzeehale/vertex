<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {

	public function index(){

		$this->setHeader(array("pageName" => "main-page"));
		$this->showView("main");
	}

	public function backup(){

		$this->load->dbutil();
        $prefs = array(     
                'format'      => 'zip',             
                'filename'    => 'top_english.sql'
              );
        $backup =& $this->dbutil->backup($prefs); 
        $db_name = 'backup-on-'. date("Y-m-d-H-i-s") .'.zip';
        //$save = '/vertex/backup/'.$db_name;

        //$this->load->helper('file');
        //write_file($save, $backup); 

        $this->load->helper('download');
        force_download($db_name, $backup); 
	}
}