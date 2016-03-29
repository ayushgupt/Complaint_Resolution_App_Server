<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class General extends REST_Controller
{

  function register_get()
  {
    $profile[0] = $this->get('username');
    $profile[1] = $this->get('password');
    $profile[2] = $this->get('kerberos_username');
    $profile[3] = $this->get('phone');
    $profile[4] = $this->get('email');
    $profile[5] = $this->get('hostel');

    $this->load->model('Data_operations');
    $data = $this->Data_operations->register($profile);
	if($data['success']=="true")
    {$response = $data ;
     $this->session->set_userdata($data['user']);
	}
	else
	{
	 $response = $data;
	}

    //json_encode($response);
    $this->response($response, 200);
  }

  function login_get()
  {
    // For debugging purposes
    //$this->output->enable_profiler(TRUE);

    $username = $this->get('username');
    $password = $this->get('password');


    $this->load->model('Data_operations');
    $response = $this->Data_operations->login($username,$password);

     $data= $response['user'];

	$this->session->set_userdata($data);
    json_encode($response);
    $this->response($response, 200);
  }

  function logout_get(){

	$this->session->sess_destroy();
        $response=array('success'=>'true') ;
		json_encode($response);
		$this->response($response, 201);
  }

  function notifications_get(){
    $startindex = $this->get('startindex');
    $endindex = $this->get('endindex');

    /*
	 if($this->session->userdata('kerberos_username')!=null)
	{

	}
    else
	{
	    $response=array('success'=>'false') ;
		json_encode($response);
		$this->response($response, 201);
	}
  */
    $limit = $endindex - $startindex +1;
    $offset = $startindex;

    $this->load->model('Data_operations');
    $response = array("notifications" => $this->Data_operations->getnotifs($limit,$offset));
    json_encode($response);
    $this->response($response, 200);
  }

}
