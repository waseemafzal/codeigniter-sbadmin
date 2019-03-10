<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	public function index()
	{ 
		if ($this->Admin_model->verifyUser()) {
			redirect(base_url(), 'auto');
		} 
	}

	public function admins($page=null, $adminid=0) {
		
		if ($this->Admin_model->verifyUser()) {
			if ($this->input->post()){
				$postData = $this->input->post();
				$this->Admin_model->updateAdmins($postData, $postData["action"]);
			}
			if ($page == "add") {
				$data["admin_groups"] = $this->Admin_model->getAdminGroups();
				$this->load->view('admins_add', $data);
			} elseif ($page == "edit") {
				if ($adminid == null) { redirect(base_url(), 'auto'); }
				$data["admin_groups"] = $this->Admin_model->getAdminGroups();
				$data["result"] = $this->Admin_model->getAdminInfo($adminid);
				$this->load->view('admins_edit', $data);
			} else {
				$data["admins"] = $this->Admin_model->getAdmins();
				$this->load->view('admins', $data);
			} 	
		}
	}

	public function groups($page=null, $groupid=0) {
		if ($this->Admin_model->verifyUser()) {
			if ($this->input->post()){
				$postData = $this->input->post();
				$this->Admin_model->updateGroups($postData, $postData["action"]);
			}
			if ($page == "add") {
				$this->load->view('admingroups_add');
			} elseif ($page == "edit") {
				$data["result"] = $this->Admin_model->getAdminGroups($groupid);
				$this->load->view('admingroups_edit', $data);
			} else {
				$data["groups"] = $this->Admin_model->getAdminGroups();
				$this->load->view('groups', $data);
			} 
		}
	}
}
