<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_cron extends Admin_Controller {

    public function __construct() {
        parent::__construct(true);
        
        $this->template->write_view('header', 'templates/header', array('page' => 'reports'));
        $this->template->write_view('footer', 'templates/footer');
        $this->load->model('Audit_model');
		$this->load->model('product_model');
		$this->load->model('user_model');
    }

	public function part_inspection_report_mail() {
        $data = array();
        $sup_id = '';
        //$data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
        $filters = array();
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				foreach($admins as $admin) {
					// $admins['email'];
					$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 240);
					$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['product_id'] = $product_id['id'];
					$data['audits'] = $this->Audit_model->get_completed_audits($filters, false);
					$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
					//echo '<pre>';print_r($data['audits']);
					$mail_content = $this->load->view('cron/mail_part_inspection_report', $data,true);
					$this->load->library('email');
					// $toemail = "komal@crgroup.co.in";
					$toemail = $admins['email'];
					$subject = "Part Inspection - Completed Inspection Report";
					//$this->sendMail($toemail,$subject,$mail_content);
				}
			}
		}
	}	
	public function lot_wise_report_mail() {
        $data = array();
        $sup_id = '';
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				foreach($admins as $admin) {			
					$filters = array();
					$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['product_id'] = $product_id['id'];
					
					$data['audits'] = $this->Audit_model->get_consolidated_audit_report($filters, false);    
					$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
					$mail_content = $this->load->view('cron/mail_lot_wise_report', $data,true);
					$this->load->library('email');
					$toemail = $admins['email'];
					$subject = "Lot wise - Completed Inspection Report";
					//$this->sendMail($toemail,$subject,$mail_content);
					//echo $this->email->print_debugger();exit;
				}
			}
		}
    }

	public function timecheck_report_mail() {
        $data = array();
        $this->load->model('Timecheck_model');
		$sup_id = '';
        $data['parts'] = $this->Audit_model->get_all_audit_parts('', $sup_id);
		$product_ids = $this->product_model->get_all_products();
		if(!empty($product_ids)){
			foreach($product_ids as $product_id) {
				$admins = $this->user_model->get_admin_users($product_id['id']);
				foreach($admins as $admin) {			
					$filters = array();			
					$filters['start_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					$filters['end_range'] = date('Y-m-d',time() - 60 * 60 * 24);
					//$filters['product_id'] = $product_id['id'];
								
					$data['plans'] = $this->Timecheck_model->get_timecheck_plan_report($filters, false);
					$data['yesterday'] = date('jS M, Y', strtotime(date('Y-m-d',time() - 60 * 60 * 24)));
					
					$mail_content = $this->load->view('cron/mail_timecheck_report', $data,true);
					$this->load->library('email');
					$toemail = $admins['email'];
					$subject = "Timecheck  - Completed Inspection Report";
					//$this->sendMail($toemail,$subject,$mail_content);
					//echo $this->email->print_debugger();exit;
				}
			}
		}
    }
		
}