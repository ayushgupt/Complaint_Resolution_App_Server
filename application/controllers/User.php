<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class User extends REST_Controller
{
  function listcomplaints_get(){
      $complaintsdata[0] = $this->get('username');
      $complaintsdata[1] = $this->get('type');
      $complaintsdata[2] = $this->get('startindex');
      $complaintsdata[3] = $this->get('endindex');

      $this->load->model('Data_operations');
      $response = $this->Data_operations->getcomplaints($complaintsdata);
      json_encode($response);
      $this->response($response, 200);
  }

  function usercomplaints_get(){
    $complaintsdata[0] = $this->get('username');
    $complaintsdata[1] = $this->get('startindex');
    $complaintsdata[2] = $this->get('endindex');

    $this->load->model('Data_operations');
    $response = $this->Data_operations->getusercomplaints($complaintsdata);
    json_encode($response);
    $this->response($response, 200);
  }

  function resolve_get(){
    $complaint_id = $this->get('key');

    $this->load->model('Data_operations');
    $response = $this->Data_operations->resolve($complaint_id);
    json_encode($response);
    $this->response($response, 200);
  }

}
