<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Crud extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Crud_model');
		
	}

	
		
	public function edit(){
		extract($_POST);
		$data = $this->Crud_model->edit($id,$table);
		if($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}
	}
	
	public function get_user_info(){
		extract($_POST);
		$data = $this->Crud_model->get_user_info($id);
		if($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}
	}
			
		public function deleteTargetByCol($id,$table,$col){ 
			extract($_POST);
			$result =$this->Crud_model->deleteTargetByCol($id,$table ,$col);
			switch($result){
				case 1:
				$arr = array('status' => 1,'message' => "Deleted Succefully !");
				echo json_encode($arr);
				break;
				case 0:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;
				default:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;	
			}
		
		}
		
		
			
			
			
		public function delete($id,$table){ 
			extract($_POST);
			$result =$this->Crud_model->delete($id,$table);
			switch($result){
				case 1:
				$arr = array('status' => 1,'message' => "Deleted Succefully !");
				echo json_encode($arr);
				break;
				case 0:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;
				default:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;	
			}
		
		}
		
		public function extract_coordinates($address)
		{ 
			extract($_POST);
			$result =$this->Crud_model->extract_coordinates($address);
			
		
		}
		
		
		
	
	public function get_last_record(){
		extract($_POST);
		$data = $this->Crud_model->get_last_record($table);
		if($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}
		
		}
		public function  changeStatus(){
			extract($_POST);
			if($status==1){
				$chageTo='Inactive';
				$status=0;
			}
			elseif ($status==0){
				$chageTo='Active';
				$status=1;
			}
			$result = $this->Crud_model->changeStatus($id,$tblName,$status);
			if($result==1){
				$arr = array('chaged' => true,'status' => $chageTo);
				echo json_encode($arr);
			}
		}
		
		public function  changeFieldStatus(){
			extract($_POST);
			if($status==1){
				$chageTo='Inactive';
				$status=0;
			}
			elseif ($status==0){
				$chageTo='Active';
				$status=1;
			}
			$result = $this->Crud_model->changeFieldStatus($id,$tblName,$status);
			if($result==1){
				$arr = array('chaged' => true,'status' => $chageTo);
				echo json_encode($arr);
			}
		}
		
		
		public function  changeLang(){
		extract($_POST);
		$result = $this->crud->set_language($id);
			if($result==true){
				$arr = array('status' => true);
			}
			else{
				$arr = array('status' => true);
				}
		   echo json_encode($arr);
			
		}
	public function get(){ 
		 //$this,$table,$field,$val;
		 extract($_POST);
		$data = $this->Crud_model->get_data($thisfields,$table,$field,$val);
		if ($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}
	}
	
		public function duplicate(){ 
		
		 //$this,$table,$field,$val;
		 extract($_POST);
		$data = $this->Crud_model->DuplicateMySQLRecord($table, $primary_key_field, $primary_key_val) ;
		if ($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}
	  }
	
	
	public function checkdupliction( $table, $primary_key_field, $primary_key_val ){ 
		
		 //$this,$table,$field,$val;
		// extract($_POST);
		 $data = $this->Crud_model->checkdupliction($table, $primary_key_field, $primary_key_val) ;
		//echo "<pre>";
		// print_r($data);
		//echo "</pre>";
		
		/*if ($data){
			$array = array('status' => 1,'data' => $data);
			echo json_encode($array);
		}
		else{
			$array = array('status' => 0,'data' => $data);
			echo json_encode($array);
		}*/
	}
	
	
	
	
	
	/******************************************************************************/
	public function deleteImage(){ 
		extract($_POST);
		$result =$this->crud->delete($id,$table);
		switch($result){
				case 1:
				$arr = array('status' => 1,'message' => "Deleted Succefully !");
				echo json_encode($arr);
				break;
				case 0:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;
				default:
				$arr = array('status' => 0,'message' => "Not Deleted!");
				echo json_encode($arr);
				break;	
			}
		
	}
		public function update_image(){ 

	extract($_POST);
	$data = array();
	if (!empty($_FILES)){ 
	/*--------------------------------------------------
	|File uploading either single or multiple
	---------------------------------------------------*/
	
	$image = $this->crud->upload_files($_FILES);
	$data['image'] =$image;
	}
	else{
		$data['image'] =$edit_image_hidden;
		$image =$edit_image_hidden;
		}	
	//pre($_POST);
	
	if(isset($description)){
		$data['description'] =$description;
		}
	if(isset($title)){
		$data['title'] =$title;
		}
	//	pre($data);	
	$result =$this->crud->update_where($edit_img_id,TBL_PAGES_CONTENT_IMAGES,$data);
	/*===============================================*/
	
	switch($result){
	case 1:
		$arr = array('status' => 1,'image' => $image,'description' => $description,'id' => $edit_img_id,'message' => "Updated Succefully !");
		echo json_encode($arr);
		break;
	case 0:
		$arr = array('status' => 0,'message' => "Not Updated!");
		echo json_encode($arr);
		break;
	default:
		$arr = array('status' => 0,'message' => "Not Updated!");
		echo json_encode($arr);
		break;	
	}
}

	
/**
*autoComplete
**/
 // end of crud.php			
}
