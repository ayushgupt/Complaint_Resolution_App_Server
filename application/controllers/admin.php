<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class admin extends REST_Controller
{
	
   function resolveC_get()
   {
       $id=$this->get('id'); 
	  $this->load->model('Data_operations');
	 
	  $response = $this->Data_operations->resolveComp($id);
	  $this->response($response, 200);
   }
   function unresolveC_get()
   {
       $id=$this->get('id'); 
	  $this->load->model('Data_operations');
	 
	  $response = $this->Data_operations->unresolveComp($id);
	  $this->response($response, 200);
   }
}
