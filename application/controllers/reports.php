<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);
        
        $this->template->write_view('header', 'templates/header', array('page' => 'reports'));
        $this->template->write_view('footer', 'templates/footer');
    }

    public function index() {
        $data = array();
        $this->load->model('Audit_model');

        if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector'){
            $sup_id = $this->supplier_id;
        }else{
            $sup_id = '';
        }
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
        
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        $data['page_no'] = 1;
        
        $data['total_records'] = 0;
        
        if(count($filters) > 1) {
			
			$_SESSION['report_filter'] = $filters;
			
            if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
                $filters['supplier_id'] = $this->supplier_id;
            }
            if(@$filters['product_all']) {
                $filters['product_id'] = "all";
            } else {
                $filters['product_id'] = $this->product_id;
            }
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Audit_model->get_completed_audits($filters, true);
            $count = $count[0]['c'];
            $data['total_records'] = $count;
            $data['total_page'] = ceil($count/50);
            
            $data['audits'] = $this->Audit_model->get_completed_audits($filters, false);
            //echo $this->db->last_query();exit;
        }
        
        $this->template->write('title', 'SQIM | Edit Inspections');
        $this->template->write_view('content', 'reports/index', $data);
        $this->template->render();
    }
	
	public function report_download($type) {
        $data = array();
        $this->load->model('Audit_model');
        
        if($type == 'report_download') {
			
			$filters = $_SESSION['report_filter'];
			
            if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
                $filters['supplier_id'] = $this->supplier_id;
            }
            if(@$filters['product_all']) {
                $filters['product_id'] = "all";
            } else {
                $filters['product_id'] = $this->product_id;
            }
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Audit_model->get_completed_audits($filters, true);
            $count = $count[0]['c'];
            $data['total_records'] = $count;
            $data['total_page'] = ceil($count/50);
            
            $data['audits'] = $this->Audit_model->get_completed_audits($filters, false);
			//echo "<pre>";print_r($data['audits']);
            //echo $this->db->last_query();exit;
			
			$str = $this->load->view("reports/report_download",$data,true);
			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=part_report.xls");
        }
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    public function lot_wise_report() {
        $data = array();
        $this->load->model('Audit_model');

        if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector'){
            $sup_id = $this->supplier_id;
        }
		else{
            $sup_id = '';
        }
        
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
        
        $this->load->model('Supplier_model');
        $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        $data['page_no'] = 1;
        
        $data['total_records'] = 0;
        
        if(count($filters) > 1) {
			
			$_SESSION['lot_report_filter'] = $filters;
            if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector'){
                $filters['supplier_id'] = $this->supplier_id;
            }
            
            if(@$filters['product_all']) {
                $filters['product_id'] = "all";
            } else {
                $filters['product_id'] = $this->product_id;
            }
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Audit_model->get_consolidated_audit_report($filters, true);
            $count = $count[0]['c'];
            $data['total_records'] = $count;
            $data['total_page'] = ceil($count/50);
            
            $data['audits'] = $this->Audit_model->get_consolidated_audit_report($filters, false, $limit);
            //echo $this->db->last_query();exit;
        }
        
        $this->template->write('title', 'SQIM | Inspections Report');
        $this->template->write_view('content', 'reports/lot_wise_report', $data);
        $this->template->render();
    }
	
    public function lot_wise_report_download($type) {
        $data = array();
        $this->load->model('Audit_model');
		if($type == 'lot_wise_report_download'){				
			$filters = $_SESSION['lot_report_filter'];			
			if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector'){
				$filters['supplier_id'] = $this->supplier_id;
			}
				
			if(@$filters['product_all']) {
				$filters['product_id'] = "all";
			} else {
				$filters['product_id'] = $this->product_id;
			}
				
			$per_page = 25;
			$page_no = $this->input->post('page_no');
			$data['page_no'] = $page_no;
			$count = $this->Audit_model->get_consolidated_audit_report($filters, true);
			$count = $count[0]['c'];
			$data['total_records'] = $count;
			$data['total_page'] = ceil($count/50);
			echo "123";
			$data['audits'] = $this->Audit_model->get_consolidated_audit_report($filters, false);
			
			$str = $this->load->view("reports/lot_wise_report_download",$data,true);
			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=lot_wise_report.xls");
        }
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
	public function part_inspection_report($audit_id) {
        $data = array();
        $this->load->model('Audit_model');
        $filters = array('id' => $audit_id);
        $audit = $this->Audit_model->get_completed_audits($filters, false, 'LIMIT 1');
        if(empty($audit)) {
            $this->session->set_flashdata('error', 'Invalid request');
            redirect(base_url().'reports');
        }
        
        $audit = $audit[0];
        $checkpoints = $this->Audit_model->get_all_audit_checkpoints($audit['id']);
        
        $max_qty = 0;
        foreach($checkpoints as $checkpoint) {
            if($checkpoint['sampling_qty'] > $max_qty) {
                $max_qty = $checkpoint['sampling_qty'];
            }
        }
        
        foreach($checkpoints as $chk){
            if($chk['result'] == 'NG'){
                $final_result = $chk['result'];
                break;
            }else{
                $final_result = $chk['result'];
            }
        }
        
        $data['final_result'] = $final_result;
        $data['audit'] = $audit;
        $data['checkpoints'] = $checkpoints;
        $data['max_qty'] = $max_qty;
        $data['total_col'] = $max_qty+13;
        
        //echo "<pre>";print_r($checkpoints); exit;
        
        if($this->input->get('download')) {
            $data['download'] = true;
            $str = $this->load->view('reports/part_inspection_report', $data, true);
        
            header('Content-Type: application/force-download');
            header('Content-disposition: attachment; filename=Part_Inspection_'.$audit['part_no'].'.xls');
            // Fix for crappy IE bug in download.
            header("Pragma: ");
            header("Cache-Control: ");
            echo $str;
        } else {
            $this->template->write('title', 'SQIM | Part Inspection Report');
            $this->template->write_view('content', 'reports/part_inspection_report', $data);
            $this->template->render();
        }
    }
    
    public function check_judgement() {
        $response = array('status' => 'error');
        if($this->input->post('audit_id')) {
            $audit_id = $this->input->post('audit_id');
            
            $this->load->model('Audit_model');
            $res = $this->Audit_model->get_audit_judgement($audit_id);
            
            $response = array('status' => 'success', 'judgement' => ($res['ng_count'] > 0 ? 'NG' : 'OK'));
        }
        
        echo json_encode($response);
    }

    public function timecheck() {
        if($this->user_type == 'Supplier Inspector') {
            //redirect($_SERVER['HTTP_REFERER']);
        }
        
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Timecheck_model');

        if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
            $sup_id = $this->supplier_id;
        }else{
            $sup_id = '';
            
            $this->load->model('Supplier_model');
            $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        }

        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);

        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
		
		//print_r($filters);exit;
        
		$data['page_no'] = 1;
        
        $data['total_records'] = 0;
        
        if(count($filters) > 1) {
			
			$_SESSION['timecheck_report_filter'] = $filters;
            if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
                $filters['supplier_id'] = $this->supplier_id;
            }
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            $limit = 'LIMIT '.($page_no-1)*$per_page.' ,'.$per_page;
            
            $data['page_no'] = $page_no;
            
            $count = $this->Timecheck_model->get_timecheck_plan_report($filters, true);
            $count = $count[0]['c'];
            $data['total_records'] = $count;
            $data['total_page'] = ceil($count/50);
            
            $data['plans'] = $this->Timecheck_model->get_timecheck_plan_report($filters, false);
            //echo $this->db->last_query();exit;
        }
        
        $this->template->write('title', 'SQIM | Timecheck Report');
        $this->template->write_view('content', 'reports/timecheck', $data);
        $this->template->render();
    }
    
    public function timecheck_download($type) {
        //echo $type;exit;
		if($this->user_type == 'Supplier Inspector') {
            //redirect($_SERVER['HTTP_REFERER']);
        }
        
        $data = array();
        $this->load->model('Audit_model');
        $this->load->model('Timecheck_model');

        if($type == 'timecheck_download') {
			$filters = $_SESSION['timecheck_report_filter'];
			
			/* if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
				$sup_id = $this->supplier_id;
			}
			$filters = $this->input->post() ? $this->input->post() : array();
			$filters = array_filter($filters);
			 */
			if($this->user_type == 'Supplier' || $this->user_type == 'Supplier Inspector') {
				$filters['supplier_id'] = $this->supplier_id;
			}
            
            $per_page = 25;
            $page_no = $this->input->post('page_no');
            
            
            $data['page_no'] = $page_no;
            
            $count = $this->Timecheck_model->get_timecheck_plan_report($filters, true);
            $count = $count[0]['c'];
            $data['total_records'] = $count;
            $data['total_page'] = ceil($count/50);
            
            $data['plans'] = $this->Timecheck_model->get_timecheck_plan_report($filters, false);
            //echo $this->db->last_query();exit;
			$str = $this->load->view("reports/timecheck_download",$data,true);
			
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=timecheck_report.xls");
        }
        
        header("Pragma: ");
		header("Cache-Control: ");
		echo $str;
    }
    
    function foolproof(){
        
        if($this->user_type == 'Supplier Inspector') {
            //redirect($_SERVER['HTTP_REFERER']);
        }
        
        $data = array();

        if($this->user_type == 'Supplier') {
            $sup_id = $this->supplier_id;
        }else{
            $sup_id = '';
            
            $this->load->model('Supplier_model');
            $data['suppliers'] = $this->Supplier_model->get_all_suppliers();
        }
        
        $filters = $this->input->post() ? $this->input->post() : array();
        $filters = array_filter($filters);
        if(count($filters) > 1) {
            if($this->user_type == 'Supplier') {
                $filters['supplier_id'] = $this->supplier_id;
            }
            
            $this->load->model('foolproof_model');
            $data['foolproofs'] = $this->foolproof_model->get_foolproof_report($filters);
            //echo $this->db->last_query();exit;
        }
        
        $this->template->write('title', 'SQIM | Fool-Proof Report');
        $this->template->write_view('content', 'reports/foolproof', $data);
        $this->template->render();
    }
    
    function download_foolproof_report($date, $supplier_id = ''){
        
        $filter = array();
        $data = array();
        
        $filter['date'] = $date;
        
        if(!empty($supplier_id)){
            $filter['supplier_id'] = $supplier_id;
        }
        
        $this->load->model('foolproof_model');
        $data['foolproofs'] = $this->foolproof_model->get_foolproof_report($filter);
        
        $str = $this->load->view('fool_proof/view', $data, true);
        
        header('Content-Type: application/force-download');
        header('Content-disposition: attachment; filename=FoolProof_Report.xls');
        // Fix for crappy IE bug in download.
        header("Pragma: ");
        header("Cache-Control: ");
        echo $str;
        
        //header("location:".base_url()."reports/foolproof");
        
    }
}