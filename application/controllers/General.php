<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class General extends REST_Controller
{

  function register_post()
  {
    $profile[0] = $this->get('username');
    $profile[1] = $this->get('password');
    $profile[2] = $this->get('kerberos_username');
    $profile[3] = $this->get('phone');
    $profile[4] = $this->get('email');
    $profile[5] = $this->get('hostel');

    $this->load->model('Data_operations');
    $data = $this->Data_operations->register($profile);
    $response = array('user'=>$data,'success'=>'true');
    $this->session->set_userdata($response);
    json_encode($response);
    $this->response($response, 200);
  }
  function addcomment_get()
  {
	$comment[0]=$this->get('id');
	$comment[1]=$this->get('content');
	$comment[2]=$this->get('postedby');
	$comment[3]=$this->get('datetime');
    
	$this->load->model('Data_operations');
   
  }

  function login_post()
  {
    // For debugging purposes
    $this->output->enable_profiler(TRUE);

    $username = $this->get('username');
    $password = $this->get('password');

    $this->load->model('Data_operations');
    $data = $this->Data_operations->login($username,$password);
    $response = array('user'=>$data,'success'=>'true');
    // store userdata in session variable
    $this->session->set_userdata($response);
    json_encode($response);
    $this->response($response, 200);
  }

  function logout_get(){
    $userarray = array('user','success');
    $this->session->unset_userdata($userarray);
  }

  function notifications_get(){
    $startindex = $this->get('startindex');
    $endindex = $this->get('endindex');

    $limit = $endindex - $startindex +1;
    $offset = $startindex;

    $this->load->model('Data_operations');
    $response = $this->Data_operations->getnotifs($limit,$offset);
    json_encode($response);
    $this->response($response, 200);
  }

}
