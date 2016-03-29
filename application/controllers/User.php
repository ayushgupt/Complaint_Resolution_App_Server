<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';


class User extends REST_Controller
{
	
  function addcomplaint_get()
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
	   $complaint[0]=$this->get('title');
	   $complaint[1]=$this->get('description');
	   $complaint[2]=$this->get('type');
       $complaint[3]=$this->get('postedby');	   
	   $complaint[4]=$this->get('admin');
		$this->load->model('Data_operations');
	 
		$response = $this->Data_operations->complaint_to_db($complaint);
		$this->response($response, 200);
	}
	
   function upvote_get()
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
	 
	  $response = $this->Data_operations->addupvote($id);
	  $this->response($response, 200);
	}
   
  function addcomment_get()
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
	$comment[0]=$this->get('id');
	$comment[1]=$this->get('contents');
	$comment[2]=$this->get('postedby');
	
    
	$this->load->model('Data_operations');
	 
    $response = $this->Data_operations->commentdb($comment);
	$this->response($response, 200);
  }
  
  function listcomplaints_get(){
     
			if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}
   	 $complaintsdata[0] = $this->get('username');
      $complaintsdata[1] = $this->get('type');
      $complaintsdata[2] = $this->get('startindex');
      $complaintsdata[3] = $this->get('endindex');

      $this->load->model('Data_operations');
      $response = $this->Data_operations->getcomplaints($complaintsdata);
      json_encode($response);
      $this->response($response, 200);
  }

  function editinfo_get()
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
			
    $info[0] = $this->get('kerberos_username');
	$info[1] = $this->get('hostel');
	$info[2] = $this->get('email');
    $info[3] = $this->get('phone');
	$info[4] = $this->get('password');
	 
	 $this->load->model('Data_operations');
     $response = $this->Data_operations->editprofile($info);
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
  
  function usercomplaints_get(){
    
     if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}	
    $complaintsdata[0] = $this->get('username');
    $complaintsdata[1] = $this->get('startindex');
    $complaintsdata[2] = $this->get('endindex');

    $this->load->model('Data_operations');
    $response = $this->Data_operations->getusercomplaints($complaintsdata);
    json_encode($response);
    $this->response($response, 200);
  }
  
  function indicomplaint_get()
  {
      /* if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}	
		*/
	  	$id=$this->get('id'); 	
		 $this->load->model('Data_operations');
		
		$response2=$this->commentlist($id) ;
		
		$response = $this->Data_operations->getindicomplaint($id);
		
		$finalresponse= array('complaint'=>$response, 'comments'=>$response2);
		json_encode($finalresponse);
		$this->response($finalresponse, 200);
  }
  function commentlist($id)
  {
     /* if($this->session->userdata('kerberos_username')!=null)
			{ 
			   
			}
			else
			{ 
				$response=array('success'=>'false') ; 
				json_encode($response);
				$this->response($response, 201);
			}	
		*/
	  	 	
		 $this->load->model('Data_operations');
		$response = $this->Data_operations->getcommentlist($id);
		return $response ;
  }
}
