<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class admin extends REST_Controller
{
	
	function superadminlogin_get()
	{
		$this->load->view('index');
	}
   function resolveC_get()
   {
       if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}
		   $id=$this->get('id'); 
		  $this->load->model('Data_operations');
		 
		  $response = $this->Data_operations->resolveComp($id);
		  if($response!=null) 
           {
		     $response=array('success'=>'true') ; 
		   }
		  else
			{
			$response=array('success'=>'false') ; 
			}
		  json_encode($response);
		  $this->response($response, 200);
   }
   function unresolveC_get()
   {    
		   if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}

      $id=$this->get('id'); 
	  $this->load->model('Data_operations');
	 
	  $response = $this->Data_operations->unresolveComp($id);
	  if($response!=null) 
           {
		     $response=array('success'=>'true') ; 
		   }
		  else
			{
			$response=array('success'=>'false') ; 
			}
		  json_encode($response);
		  $this->response($response, 200);
	  
   }
   function admincomplaint_get()
   {
      
	   if($this->session->userdata('kerberos_username')!=null)
		{ 
		   
		}
		else
		{ 
			$response=array('success'=>'false') ; 
			json_encode($response);
			$this->response($response, 201);
		}
      $type=$this->get('admin');   
      
      $this->load->model('Data_operations');
      $response = $this->Data_operations->getadmincomplaints($type);
      json_encode($response);
      $this->response($response, 200);	  
   }
  function adminlogin_get()
  {
    // For debugging purposes
    //$this->output->enable_profiler(TRUE);

    $username = $this->get('username');
    $password = $this->get('password');

	 
    $this->load->model('Data_operations');
    $response = $this->Data_operations->adminlogin($username,$password);
    
     $data= $response['user']; 
	 
	$this->session->set_userdata($data);
    json_encode($response);
    $this->response($response, 200);
  }
  function adminedit_get()
  {
	$username = $this->get('username');
    $password = $this->get('password');
	$info= array('username'=>$username,
	 'password'=>$password
	);
	$this->load->model('Data_operations');
    $response = $this->Data_operations->editadmin($info);
	
  }
}
