<?php

class Data_operations extends CI_Model {

   function __construct()
   {
     parent::__construct() ;
   }

   function commentdb($comment)
   {
     $this->load->database();
     $this->db->distinct();

      //decode all elements of array
      foreach ( $comment as &$val ){
  			$val = rawurldecode($val) ;
  		}
    $this->load->helper('date');
	$datestring = "%Y-%m-%d %h:%i:%s";
    $time = time();

    $t= mdate($datestring, $time);
	
     $data = array(
      'id'=>$comment[0],
      'contents'=>$comment[1],
      'postedby'=>$comment[2],
	  'datetime'=>$t
	);

	$indi_notif=$this->getnotifbyid($comment[0]);
	
	$notif = array(
      'description'=>$comment[2].' commented on '.$indi_notif['title'][0],
	  'datetime_created'=>$t
	);
	//insert into notification table
	 $this->db->insert('notifications', $notif);
    //insert values into users table of database
    $this->db->insert('comments', $data);
    return "true" ;
   
   }
   
   public function getnotifbyid($id)
   {
    $this->load->database(); 
    $query= $this->db->query('SELECT * FROM complaints WHERE id='.$id) ;                
    $i= 0 ;
	 foreach($query->result() as  $row) 
         {  $result['title'][$i] = $row->title ;                                         
			$result['description'][$i]= $row->description ;        
			$result['type'][$i] = $row->type ;
			$result['admin'][$i] =$row->admin ;
			$result['datetime_created'][$i]= $row->datetime_created ;
			$result['datetime_last'][$i]= $row->datetime_last ;
			$i=$i+1 ;
		 }
		
		return $result ;
   }
   function register($profile){
     $this->load->database();
     $this->db->distinct();

      //decode all elements of array
      foreach ( $profile as &$val ){
  			$val = rawurldecode($val) ;
  		}

    $data = array(
      'name'=>$profile[0],
      'kerberos_username'=>$profile[1],
      'hostel'=>$profile[2],
	  'email'=>$profile[3],
	  'phone'=>$profile[4],
	  'password'=>$profile[5]
	);

    //insert values into users table of database
    $this->db->insert('users', $data);

    $this->db->from('users');
    $this->db->where('name=',$profile[0]);
    $this->db->where('password=',$profile[1]);
    $query = $this->db->get();
	
	return $query->result()[0];
   }

   function login($username,$password){
     $this->load->database();
     $this->db->distinct();

     //decode all elements of array
     $username = rawurldecode($username);
     $password = rawurldecode($password);

     $this->db->from('users');
     $this->db->where('name=',$username);
     $this->db->where('password=',$password);
     $query = $this->db->get();
     return $query->result()[0];
   }

   function getnotifs($limit,$offset){
     $this->load->database();

     $limit = rawurldecode($limit);
     $offset = rawurldecode($offset);

     $query = $this->db->get('notifications',$limit,$offset);
     return $query->result();
   }

   function getcomplaints($complaintsdata){
     $this->load->database();

     //decode all elements of array
     foreach ( $complaintsdata as &$val ){
       $val = rawurldecode($val) ;
     }

     $type = $complaintsdata[1];
     if($type[0]=='0'){
       $this->db->where('postedby=',$complaintsdata[0]);
       $this->db->where('type=',$type);
       $offset = $complaintsdata[2];
       $limit = $complaintsdata[3]-$complaintsdata[2]+1;
       $query = $this->db->get('complaints',$limit,$offset);
     }
     elseif($type[0]=='1') {
       $this->db->where('type=',$type);  // this compares type as well as hostel
       $offset = $complaintsdata[2];
       $limit = $complaintsdata[3]-$complaintsdata[2]+1;
       $query = $this->db->get('complaints',$limit,$offset);
     }
     else {
       $this->db->where('type=',$type);
       $offset = $complaintsdata[2];
       $limit = $complaintsdata[3]-$complaintsdata[2]+1;
       $query = $this->db->get('complaints',$limit,$offset);
     }

     return $query->result();
   }

   function getusercomplaints($complaintsdata){
     $this->load->database();

     //decode all elements of array
     foreach ( $complaintsdata as &$val ){
       $val = rawurldecode($val) ;
     }

     $username = $complaintsdata[0];
     $this->db->where('postedby=',$username);
     $offset = $complaintsdata[1];
     $limit = $complaintsdata[2]-$complaintsdata[1]+1;
     $query = $this->db->get('complaints',$limit,$offset);
     return $query->result();
   }

   function resolve($complaint_id){
     $this->load->database();
     $data = array('resolved'=>'1');
     $this->db->where('id=',$complaint_id);
     $query = $this->db->update('complaints',$data);


     $this->db->where('id=',$complaint_id);
     $complaintquery = $this->db->get('complaints');
     return $complaintquery->result()[0];
   }

}
