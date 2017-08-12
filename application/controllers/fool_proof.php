<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class fool_proof extends Admin_Controller {
        
    public function __construct() {
        parent::__construct();

        //render template
        $this->template->write('title', 'SQIM | '.$this->user_type.' Dashboard');
        $this->template->write_view('header', 'templates/header', array('page' => 'masters'));
        $this->template->write_view('footer', 'templates/footer');

    }
        
    public function index() {
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($this->product_id);
        $data['product'] = $product;
        
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
        
        $this->load->model('foolproof_model');
        
        //$checkpoints = array();
        //if($this->input->get('part_no')) {
            $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
            
            $checkpoints = $this->foolproof_model->get_all_checkpoints($supplier_id);
            //echo $this->db->last_query();exit;
        //}
        
        $data['checkpoints'] =  $checkpoints;

        $this->template->write_view('content', 'fool_proof/index', $data);
        $this->template->render();
    }
    
    public function pc_mappings() {
        $data = array();
        $this->load->model('Product_model');
        $product = $this->Product_model->get_product($this->product_id);
        $data['product'] = $product;
        
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
        
        $this->load->model('foolproof_model');
        
        $mappings = array();
        if($this->input->get('part_no')) {
            $supplier_id = $this->user_type == 'Supplier' ? $this->id : '';
            
            $mappings = $this->foolproof_model->get_mappings($this->input->get('part_no'), $supplier_id);
            //echo $this->db->last_query();exit;
        }
        
        $data['mappings'] =  $mappings;
        
        //print_r($mappings); exit;

        $this->template->write_view('content', 'fool_proof/pc_mappings', $data);
        $this->template->render();
    }
    
	public function pf_mappings() {
        $data = array();
        $this->load->model('Product_model');
        $this->load->model('foolproof_model');
        $data['products'] = $this->Product_model->get_all_products();
		$sid = $this->supplier_id;
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier_new($this->product_id,$sid);
        
		$data['foolproofs'] = $this->foolproof_model->get_all_foolproofs($sid);
        //echo $this->db->last_query(); exit;
        if($this->input->post('part_name')){			
			$data['part_nums'] = $this->Product_model->get_all_product_parts_by_supplier_new($this->product_id,$sid);
        }
		
		$filters = $this->input->post() ? $this->input->post() : array() ;
		
		if($filters){
            
            $data['part_nums'] = $this->Product_model->parts_foolproof_map($filters,$this->product_id);
            $data['pf_mapping'] = array_column($this->foolproof_model->pf_mappings_view($filters,$this->product_id),'part_num');
		}else{
            $data['part_nums'] = '';
            //$data['sp_mappings'] = '';
        } 
        

        $this->template->write_view('content', 'fool_proof/pf_mappings', $data);
        $this->template->render();
    }
    
    public function add_checkpoint($checkpoint_id = '') {
        $data = array();

        $this->load->model('foolproof_model');
        $data['existing_checkpoints'] = '';
        
        if(!empty($checkpoint_id)) {
            $checkpoint = $this->foolproof_model->get_checkpoint($checkpoint_id);
            if(empty($checkpoint))
                redirect(base_url().'fool_proof');
            
            if($this->user_type != 'Supplier') {
                $this->session->set_flashdata('error', 'Permission Error.');
                redirect(base_url().'fool_proof');
            }

            $data['checkpoint'] = $checkpoint;
        }
        
        $this->load->model('Product_model');
        
        //$data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);

        if($this->input->post()) {
            
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('stage', 'Stage', 'trim|required|xss_clean');
            $validate->set_rules('sub_stage', 'Sub Stage', 'trim|required|xss_clean');
            $validate->set_rules('major_control_parameters', 'Major Control Parameters', 'trim|required|xss_clean');
            $validate->set_rules('measuring_equipment', 'Measuring Equipment', 'trim|required|xss_clean');
            $validate->set_rules('cycle', 'No. of Days', 'trim|required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                //$part = $this->Product_model->get_product_part($this->product_id, $post_data['part_id']);
                $post_data['product_id'] = $this->product_id;
                
                $id = !empty($checkpoint['id']) ? $checkpoint['id'] : '';
                $checkpoint_no = $this->input->post('checkpoint_no');
                    
                $post_data['checkpoint_type'] = 'Supplier';
                $post_data['supplier_id'] = $this->id;
                $exists = $this->foolproof_model->check_duplicate_checkpoint($post_data);
                if($exists) {
                    //$this->foolproof_model->move_checkpoints($this->product_id, $post_data['part_id'], $checkpoint_no);
                    $data['error'] = 'Duplicate Checkpoint';
                }
                
                $post_data['period'] = 'Days';
                
                if(!isset($data['error'])){
                    $checkpoint_id = $this->foolproof_model->update_checkpoint($post_data, $id);
                    $post_data['checkpoint_id'] = $checkpoint_id;
                    if($checkpoint_id) {
                        $type = !empty($id) ? 'Updated' : 'Added';
                        $before = !empty($checkpoint) ? $checkpoint : array();

                        //$this->add_history($before, $this->product_id, $checkpoint_id, $type, $this->input->post('remark'));
                        $this->session->set_flashdata('success', 'Checkpoint Successfully '.(($checkpoint_id) ? 'Updated' : 'Added').'.');
                        redirect(base_url().'fool_proof?part_no='.$part['code']);
                    } else {
                        $data['error'] = 'Something went wrong, Please try again';
                    }
                }
                
            } else {
                $data['error'] = validation_errors();
            }
            
        }

        $this->template->write_view('content', 'fool_proof/add_checkpoint', $data);
        $this->template->render();
    }
    
    public function add_pc_mapping($mapping_id = '') {
        $data = array();

        $this->load->model('foolproof_model');
        $data['existing_checkpoints'] = '';
        
        $stage = '';
        $sub_stage = '';
        
        if(!empty($mapping_id)) {
            $mapping = $this->foolproof_model->get_mapping($mapping_id);
            if(empty($mapping))
                redirect(base_url().'fool_proof/pc_mappings');
            
            if($this->user_type != 'Supplier') {
                $this->session->set_flashdata('error', 'Permission Error.');
                redirect(base_url().'fool_proof');
            }

            $data['mapping'] = $mapping;
            $stage = $mapping['stage'];
            $sub_stage = $mapping['sub_stage'];
        }
        
        $this->load->model('Product_model');
        
        $data['parts'] = $this->Product_model->get_all_product_parts_by_supplier($this->product_id, $this->supplier_id);
        $data['stages'] = $this->foolproof_model->get_unique_stages_by_supplier($this->supplier_id);
        $data['sub_stages'] = $this->foolproof_model->get_unique_sub_stages_by_supplier($this->supplier_id, $stage);
        $data['mcps'] = $this->foolproof_model->get_unique_mcp_by_supplier($this->supplier_id, $stage, $sub_stage);

        if($this->input->post()) {
            
            $this->load->library('form_validation');

            $validate = $this->form_validation;
            $validate->set_rules('stage', 'Stage', 'trim|required|xss_clean');
            $validate->set_rules('sub_stage', 'Sub Stage', 'trim|required|xss_clean');
            $validate->set_rules('major_control_parameters', 'Major Control Parameters', 'trim|required|xss_clean');
            $validate->set_rules('part_id', 'Part', 'trim|required|xss_clean');
            
            if($validate->run() === TRUE) {
                $post_data = $this->input->post();
                $part = $this->Product_model->get_product_part($this->product_id, $post_data['part_id']);
                $post_data['product_id'] = $this->product_id;
                
                $id = !empty($mapping['id']) ? $mapping['id'] : '';
                
                $checkpoint_id = $this->foolproof_model->check_duplicate_checkpoint($post_data, $this->supplier_id);
                if($checkpoint_id){
                    $exist_data = array();
                    $exist_data['checkpoint_id'] = $checkpoint_id['id'];
                    $exist_data['part_id'] = $post_data['part_id'];
                    $exist_data['is_deleted'] = 0;
                    $exists_chk = $this->foolproof_model->check_duplicate_pc_mapping($exist_data);
                }else{
                    $data['error'] = 'Checkpoint does not exist!';
                }
                //echo "<pre>"; print_r($exists_chk); exit;
                if(!empty($exists_chk)) {
                    $data['error'] = 'Mapping Already Exists!';
                }
                //echo $data['error']; exit;
                if(!isset($data['error'])){
                    $mapping_id = $this->foolproof_model->update_pc_mapping($exist_data, $id);
                    $post_data['mapping_id'] = $mapping_id;
                    if($mapping_id) {
                        $this->session->set_flashdata('success', 'Mapping Successfully '.(($mapping_id) ? 'Updated' : 'Added').'.');
                        redirect(base_url().'fool_proof/pc_mappings?part_no='.$part['code']);
                        
                    } else {
                        $data['error'] = 'Something went wrong, Please try again';
                    }
                }
                
            } else {
                $data['error'] = validation_errors();
            }
            
        }

        $this->template->write_view('content', 'fool_proof/add_pc_mapping', $data);
        $this->template->render();
    }
    
    public function upload_checkpoints() {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
        if($this->input->post()) {
             
            if(!empty($_FILES['checkpoints_excel']['name'])) {
                $output = $this->upload_file('checkpoints_excel', 'checkpoints_excel', "assets/uploads/");

                if($output['status'] == 'success') {
                    $res = $this->parse_checkpoints($output['file']);
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Checkpoints successfully uploaded.');
                        redirect(base_url().'fool_proof');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'fool_proof/upload_checkpoints', $data);
        $this->template->render();
    }
    
    public function upload_pc_mappings() {
        $data = array();
        $this->load->model('Product_model');
        
        $product = $this->Product_model->get_product($this->product_id);
        if(empty($product))
            redirect(base_url().'products');
        
        $data['product'] = $product;
        
        if($this->input->post()) {
             
            if(!empty($_FILES['checkpoints_excel']['name'])) {
                $output = $this->upload_file('checkpoints_excel', 'checkpoints_excel', "assets/uploads/");

                if($output['status'] == 'success') {
                    $res = $this->parse_pc_mappings($this->product_id,$output['file']);
                    
                    if($res) {
                        $this->session->set_flashdata('success', 'Checkpoints successfully uploaded.');
                        redirect(base_url().'fool_proof');
                    } else {
                        $data['error'] = 'Error while uploading excel';
                    }
                } else {
                    $data['error'] = $output['error'];
                }

            }
        }
        
        $this->template->write_view('content', 'fool_proof/upload_pc_mappings', $data);
        $this->template->render();
    }
    
    private function parse_checkpoints($file_name) {
        
        ini_set('memory_limit', '10M');
        
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $this->load->model('Product_model');
        $this->load->model('foolproof_model');
        
        $checkpoints = array();
        $i=0; $j=0;
        foreach($arr as $no => $row) {
            
            if($no == 1)
                continue;
            
            if(!trim($row['B']))
                continue;
            
            if(!trim($row['C']))
                continue;
            
            if(!trim($row['D']))
                continue;
            
            if(!trim($row['E']) || !is_numeric(trim($row['E'])))
                continue;
            
            $temp = array();
            
            $temp['stage']                      = trim($row['B']);
            $temp['sub_stage']                  = trim($row['C']);
            $temp['major_control_parameters']   = trim($row['D']);
            $temp['period']                     = 'Days';
            $temp['cycle']                      = trim($row['E']);
            $temp['unit']                       = trim($row['G']);
            $temp['lsl']                        = trim($row['H']);
            $temp['tgt']                        = trim($row['I']);
            $temp['usl']                        = trim($row['J']);
            $temp['measuring_equipment']        = trim($row['K']);
            
            $temp['supplier_id']                = $this->supplier_id;
            
            IF(trim($row['L']) == 'Y')
                $temp['is_deleted'] = 0;
            ELSE IF(trim($row['L']) == 'N')
                $temp['is_deleted'] = 1;
            ELSE
                CONTINUE;
            
            $temp['created']                    = date("Y-m-d H:i:s");
            
            $exists_chk = $this->foolproof_model->check_duplicate_checkpoint($temp, $temp['supplier_id']);
            
            if($exists_chk) {
                //echo "update";
                $i++;
                $this->foolproof_model->update_checkpoint($temp, $exists_chk['id']);
            } else {
                $j++;
                //echo "insert";
                $checkpoints[]        = $temp;
            }
        }
        
        if(!empty($checkpoints)) {
            $this->foolproof_model->insert_checkpoints($checkpoints);
        }
        
        //echo $i." Update and ".$j." Inserted"; exit;
        
        return TRUE;
    }
    
    private function parse_pc_mappings($product_id, $file_name) {
        
        ini_set('memory_limit', '10M');
        
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file_name);
        
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
        $arr = $objPHPExcel->getActiveSheet()->toArray(null, true,true,true);
        
        if(empty($arr) || !isset($arr[1])) {
            return FALSE;
        }
        
        $this->load->model('Product_model');
        $this->load->model('foolproof_model');
        
        $checkpoints = array();
        $parts = array();
        $part_no = '';
        $part_id = '';
        $i=0; $j=0;
        foreach($arr as $no => $row) {
            
            if($no == 1)
                continue;
            
            if(!trim($row['B']))
                continue;

            if(!trim($row['C']))
                continue;
            
            if(!trim($row['D']))
                continue;
            
            if(!trim($row['E']))
                continue;
            
            if(!trim($row['F']))
                continue;
            
            //echo "here"; exit;
            
            $part_no = trim($row['E']);
            if(!array_key_exists($part_no, $parts)) {
                $exists = $this->Product_model->get_product_part_by_code($product_id, $part_no);
                if(empty($exists)) {
                    continue;
                } else {
                    $part_id = $exists['id'];
                }

                $parts[$part_no] = $part_id;
            }
            
            $temp = array();
            
            $temp['stage']                      = trim($row['B']);
            $temp['sub_stage']                  = trim($row['C']);
            $temp['major_control_parameters']   = trim($row['D']);
            
            $ch_exists = $this->foolproof_model->check_duplicate_checkpoint($temp, $this->supplier_id);
            //echo "<pre>"; print_r($ch_exists); exit;
            if(empty($ch_exists)){
                continue;
            }else{
                $temp = array();
                $temp['checkpoint_id']          = $ch_exists['id'];
                $temp['part_id']                = $parts[$part_no];
            }
            
            IF(trim($row['G']) == 'Y')
                $temp['is_deleted'] = 0;
            ELSE IF(trim($row['G']) == 'N')
                $temp['is_deleted'] = 1;
            ELSE
                CONTINUE;
            
            $temp['created']            = date("Y-m-d H:i:s");
            
            //echo "<pre>"; print_r($temp); exit;
            
            $exists_chk = $this->foolproof_model->check_duplicate_pc_mapping($temp);
            
            if($exists_chk) {
                //echo "update";
                $i++;
                $this->foolproof_model->update_pc_mapping($temp, $exists_chk['id']);
            } else {
                $j++;
                //echo "insert";
                $checkpoints[]        = $temp;
            }
        }
        
        if(!empty($checkpoints)) {
            $this->foolproof_model->insert_pc_mappings($checkpoints, $product_id);
        }
        
        //echo $i." Update and ".$j." Inserted"; exit;
        
        return TRUE;
    }
    
    public function start() {
        $this->load->model('foolproof_model');

        $checkpoints = $this->foolproof_model->get_checkpoints($this->supplier_id);
        
        $data['checkpoints'] = $checkpoints;
        
        $this->template->write('title', 'SQIM | Fool Proof');
        $this->template->write_view('content', 'fool_proof/foolproof', $data);
        $this->template->render();
    }
    
    function save_result() {
        //print_r($this->input->post());
        if($this->input->post()){

            $this->load->model('foolproof_model');
            $checkpoint = $this->foolproof_model->get_checkpoint($this->input->post('checkpoint_id'));
            if($this->input->post('all_results') == 'NP'){
                //echo "here 1";
                $data['all_results'] = $this->input->post('all_results');
                $data['all_values'] = '';
                $data['result'] = $this->input->post('all_results');
            }else if($this->input->post('all_values') == 'NP'){
                //echo "here 2";
                $data['all_results'] = '';
                $data['all_values'] = $this->input->post('all_values');
                $data['result'] = $this->input->post('all_values');
            }else{
                //echo "here 3 ".$this->input->post('all_results')."end ";
                if($this->input->post('all_results') === '0'){
                    echo "here 4";
                    $data['all_results'] = '';
                    $data['all_values'] = $this->input->post('all_values');
                    if(($this->input->post('all_values') > $checkpoint['usl']) || ($this->input->post('all_values') < $checkpoint['lsl'])){
                        $data['result'] = 'NG';
                    }else{
                        $data['result'] = 'OK';
                    }
                }else{
                    //echo "here 5";
                    $data['all_results'] = $this->input->post('all_results');
                    $data['all_values'] = '';
                    $data['result'] = $this->input->post('all_results');
                }
            }
            
            //$post    = $this->input->post('image');
            //$post    = json_decode($post, true);
            if($_FILES){
                $upload = $this->upload_image($_FILES);
                if($upload){
                    $data['image'] = $_FILES['image']['name'];
                }else{
                    return false;
                }
            }else{
                $data['image'] = '';
            }
			if($data['result'] == 'NG'){
				$this->load->model('Phone_model');
				$this->load->model('foolproof_model');
				
				//$pf_mapping = $this->foolproof_model->get_parts_foolproof_by_foolproof(102);//$checkpoint['id']
				//$phone_numbers = $this->Phone_model->get_all_phone_numbers_by_product($this->product_id);//supplierid
				//send_sms
				//print_r($phone_numbers);exit;
				
				$phone_numbers = $this->Phone_model->get_all_phone_numbers(102);//supplierid
				if(!empty($phone_numbers)) {
					$to = array();
					
					foreach($phone_numbers as $phone_number) {
						$to[] = $phone_number['phone_number'];
						$name = $phone_number['supplier_name'];
					}
					
					$to = implode(',', $to);
					$sms = $name." - SQIM - Foolproof Result NG. Stage - (".$checkpoint['stage'].")";
					
					$ip_address = $this->get_server_ip();
					if($ip_address == '202.154.175.50'){
						
						if(isset($to) && isset($sms)){
							$sms1= urlencode($sms);
							$to1 = urlencode($to);
							$data = array('to' => $to1, 'sms' => $sms1);
							$url = "http://10.101.0.80:90/SQIM/fool_proof/send_sms_redirect";    	
							//$url = "http://localhost/PRTMS_NEW/apps/send_sms_redirect";    	

							$ch = curl_init();
									curl_setopt_array($ch, array(
									CURLOPT_URL => $url,
									CURLOPT_RETURNTRANSFER => true,
									CURLOPT_POSTFIELDS => $data,
							));
							//get response
							$output = curl_exec($ch);
							$flag = true;
							//Print error if any
							if(curl_errno($ch))
							{
									$flag = false;
							}
							curl_close($ch);
						}
					}else{
						$this->send_sms($to, $sms);
					}
				}
				
			}
            $data = array_merge($checkpoint,$data);
            
            $this->foolproof_model->insert_result($data);
            //echo $this->db->last_query();
            return true;
            //redirect($_SERVER['HTTP_REFERER']);
        }else{
            return false;
        }
    }
    
    function upload_image($image){
        $target_dir = "assets/foolproof_captured/";
        $target_file = $target_dir . basename($image['image']['name']);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        // Check if image file is a actual image or fake image
        $check = getimagesize($image['image']['tmp_name']);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            move_uploaded_file($image['image']['tmp_name'], $target_file);
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        
        return $uploadOk;
    }
    
    public function checkpoint_approval_index(){
        
        $data = array();
        
        $this->load->model('foolproof_model');
        $data['approval_items'] = $this->foolproof_model->get_pending_checkpoints($this->product_id);
        
        $this->template->write_view('content', 'fool_proof/checkpoint_approval_index', $data);
        $this->template->render();
    }
    
	
	
	public function change_checkpoints_status_all($status){
        
		//echo $status;exit;
        $data = array();
        $this->load->model('foolproof_model');
        
        $update_status = $this->foolproof_model->change_status_all($status);
        
        if($update_status) {
            $this->session->set_flashdata('success', 'Inspection Item successfully Approved.');
        } else {
            $this->session->set_flashdata('error', 'Inspection Item Declined.');
        }
        
        redirect(base_url().'fool_proof/checkpoint_approval_index');
    }
	
    public function checkpoint_status($checkpoint_id, $status){
        
        $data = array();
        $this->load->model('foolproof_model');
        
        $update_status = $this->foolproof_model->change_status($checkpoint_id, $status);
        
        if($update_status && $status == 'Approved') {
            $this->session->set_flashdata('success', 'Inspection Item successfully Approved.');
        } else {
            $this->session->set_flashdata('error', 'Inspection Item Declined.');
        }
        
        redirect(base_url().'fool_proof/checkpoint_approval_index');
    }

	public function delete_checkpoint($checkpoint_id){
        
        $data = array();
        $this->load->model('foolproof_model');
        
        echo $update_status = $this->foolproof_model->hide_fp_checkpoints($checkpoint_id);
        
        if($update_status) {
            $this->session->set_flashdata('success', 'Foolproof Checkpoint deleted.');
        } else {
            $this->session->set_flashdata('error', 'Foolproof Checkpoint not deleted.');
        }
        
        redirect(base_url().'fool_proof');
    }
	
	function save_pf_mapping(){
		$data = array('map' => array());
        if($this->input->post('part_id') && $this->input->post('foolproof_id')) {
            $this->load->model('foolproof_model');
            $data['map'] = $this->foolproof_model->save_pf($this->input->post('part_id'), $this->input->post('foolproof_id'),$this->input->post('s'));
        }
		//echo $this->db->last_query();
		echo json_encode($data);
	}
	
	/* function pf_mappings_view(){
		echo "abc";
		$this->load->model('foolproof_model');
        $data['map'] = $this->foolproof_model->pf_mappings_view();
		echo '<pre>';print_r($data['map']);exit;
	} */
	
	public function pf_mappings_view() {
        $data = array();
        $this->load->model('Product_model');
		$this->load->model('foolproof_model');
        $data['products'] = $this->Product_model->get_all_products();
        $data['parts'] = $this->Product_model->get_all_distinct_part_name($this->product_id,$this->supplier_id);
        $sid = $this->supplier_id;
		$data['foolproofs'] = $this->foolproof_model->get_all_foolproofs($sid);
        $filters = $this->input->post() ? $this->input->post() : array() ;
		// print_r($filters);exit;
		if($this->input->post()){            
            $data['part_nums'] = $this->Product_model->get_all_part_numbers_by_part_name($this->input->post('part_name'));
		    $data['sp_mappings'] =  $this->foolproof_model->pf_mappings_view($filters,$this->product_id);
		}else{
            $data['part_nums'] = '';
            $data['sp_mappings'] = '';
        }
        $this->template->write_view('content', 'fool_proof/pf_mappings_view', $data);
        $this->template->render();
    }
	
	public function get_parts_foolproof_by_foolproof() {
        $data = array('foolproofs' => array());
            $this->load->model('foolproof_model');
            $this->load->model('Product_model');
       
		$data['foolproofs'] = $this->foolproof_model->get_all_foolproofs($this->supplier_id);
        if($this->input->post('part_name')){			
			$data['part_nums'] = $this->Product_model->get_all_product_parts_by_supplier_new($this->product_id,$this->supplier_id);
        }
		
		$filters = $this->input->post() ? $this->input->post() : array() ;
		
		if($filters){            
            $data['part_nums'] = $this->Product_model->parts_foolproof_map($filters,$this->product_id);
            $data['pf_mapping1'] = $this->foolproof_model->pf_mappings_view_check($filters,$this->product_id);
			$data['foolproof_id'] = $this->input->post('foolproof_id');
		}else{
            $data['part_nums'] = '';
        } 
           

        $str = $this->load->view('fool_proof/pf_mappings_table', $data,true);
		$str = stripslashes($str);
		echo json_encode($str);
    }
    
}