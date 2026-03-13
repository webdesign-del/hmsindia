<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_forms extends CI_Controller {

	public function __construct()
	{
		// Load parent's constructor.
       	parent::__construct();
		$this->load->database();
		$this->load->helper('form');
        $this->load->helper('url_helper');
	    $this->load->library('session');
		$this->load->model(array('doctors_model', 'patients_model', 'center_model', 'employee_model', 'appointment_model', 'billingmodel_model', 'investigation_model', 'procedures_model', 'stock_model','accounts_model','billings_model','Embryology_model','Procedureform_model'));
		$this->load->helper('myhelper');
		$this->load->library("pagination");
	}	
	

    public function embryo_record_list()
    {
        $logg = checklogin();
		if($logg['status'] == true){		
		$data = array();
        $patient_id = $this->input->get('patient_id');

        $config['base_url'] = base_url('Procedure_forms/embryo_record_list');

        $config['total_rows'] = $this->Procedureform_model->count_records($patient_id);

        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['records'] = $this->Procedureform_model->get_records($config['per_page'],$page,$patient_id);

        $data['pagination'] = $this->pagination->create_links();

            $template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('Procedure_forms/embryo_record_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
    }

    public function approve($id)
    {

        $this->Procedureform_model->update_status($id,'approved');

        redirect('embryo_records');
    }

    public function reject($id)
    {

        $this->Procedureform_model->update_status($id,'rejected');

        redirect('embryo_records');
    }

        public function trigger_module_list()
    {
        $logg = checklogin();
		if($logg['status'] == true){		
		$data = array();
        $patient_id = $this->input->get('patient_id');

        $config['base_url'] = base_url('Procedure_forms/trigger_module_list');

        $config['total_rows'] = $this->Procedureform_model->trigger_count_records($patient_id);

        $config['per_page'] = 20;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['records'] = $this->Procedureform_model->trigger_get_records($config['per_page'],$page,$patient_id);

        $data['pagination'] = $this->pagination->create_links();

            $template = get_header_template($logg['role']);
			$this->load->view($template['header']);
			$this->load->view('Procedure_forms/trigger_module_list', $data);
			$this->load->view($template['footer']);
		}else{
			header("location:" .base_url(). "");
			die();
		}
    }

    public function trigger_approve($id)
    {

        $this->Procedureform_model->trigger_update_status($id,'approved');

        redirect('trigger_module');
    }

    public function trigger_reject($id)
    {

        $this->Procedureform_model->trigger_update_status($id,'rejected');

        redirect('trigger_module');
    }

}