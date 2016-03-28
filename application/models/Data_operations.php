<?php

class Data_operations extends CI_Model {

   function __construct()
   {
     parent::__construct() ;
   }

   function unresolveComp($id)
   {
     $this->load->database();
     $this->db->distinct();
	 //obtaining date
	 $this->load->helper('date');
	 $datestring = "%Y-%m-%d %h:%i:%s";
     $time = time();
     $t= mdate($datestring, $time);
   
     //updating resolve	 
     $this->db->set('resolved', 'resolved-1', FALSE);
	 $this->db->where('id', $id);
	 $this->db->update('complaints');
	 
   	return true ;
   }
   function resolveComp($id)
   {
     $this->load->database();
     $this->db->distinct();
	 //obtaining date
	 $this->load->helper('date');
	 $datestring = "%Y-%m-%d %h:%i:%s";
     $time = time();
     $t= mdate($datestring, $time);
   
     //updating resolve	 
     $this->db->set('resolved', 'resolved+1', FALSE);
	 $this->db->where('id', $id);
	 $this->db->update('complaints');
	 
	 //adding notifications
	 $indi_notif=$this->getnotifbyid($id);
	 $notif=array(
	 'description'=> $indi_notif['title'][0].' is resolved',
	 'datetime_created'=> $t
	 );
	 $this->db->insert('notifications',$notif); 
   	return true ;
	 
   }
   function addupvote($id)
   {
     $this->load->database();
     $this->db->distinct();
	 //obtaining date
	 $this->load->helper('date');
	 $datestring = "%Y-%m-%d %h:%i:%s";
     $time = time();
     $t= mdate($datestring, $time);
	 
     //updating upvote	 
     $this->db->set('upvotes_num', 'upvotes_num+1', FALSE);
	 $this->db->where('id', $id);
	 $this->db->update('complaints');
	 
	 //adding notifications
	 $indi_notif=$this->getnotifbyid($id);
	 $notif=array(
	 'description'=> 'Upvote on '.$indi_notif['title'][0],
	 'datetime_created'=> $t
	 );
	 $this->db->insert('notifications',$notif); 
   	return true ;
   }
   function complaint_to_db($newcomplaint)
   {
      $this->load->database();
      $this->db->distinct();

      //decode all elements of array
      foreach ( $newcomplaint as &$val ){
  			$val = rawurldecode($val) ;
  		}
     //obtaining date
	 $this->load->helper('date');
	 $datestring = "%Y-%m-%d %h:%i:%s";
     $time = time();
     $t= mdate($datestring, $time);
     
     $data=array(
	 'title'=>$newcomplaint[0],
	 'description'=>$newcomplaint[1],
	 'type'=>$newcomplaint[2],
	  'postedby'=>$newcomplaint[3],
	  'admin'=>$newcomplaint[4],
	  'datetime_created'=>$t,
	  'datetime_last'=>$t, 
	  'upvotes_num'=>0,
	  'comments_num'=>0 ,
	  'resolved'=>0 
	 );
	 
	 //inserting new complaint into table
	 $this->db->insert('complaints', $data);
	 
	 $notif=array(
	 'description'=> $newcomplaint[3].' posted a new complaint',
	 'datetime_created'=> $t
	 );
	 //inserting new notification
	 $this->db->insert('notifications',$notif); 
	 return true; 
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
	 //echo $comment[1] ;
	//insert values into comments table of database
     $data = array(
      'id'=>$comment[0],
      'contents'=>$comment[1],
      'postedby'=>$comment[2],
	  'datetime'=>$t
	);
    $this->db->insert('comments', $data);
	//insert into notification table
	$indi_notif=$this->getnotifbyid($comment[0]);
	$notif = array(
      'description'=>$comment[2].' commented on '.$indi_notif['title'][0],
	  'datetime_created'=>$t
	);
	$this->db->insert('notifications', $notif);
    
    
    //incrementing comment number 
	$this->db->set('comments_num', 'comments_num+1', FALSE);
	 $this->db->where('id', $comment[0]);
	 $this->db->update('complaints');
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
   function editprofile($info)
   {
      $this->load->database();
	    //decode all elements of array
      foreach ( $info as &$val ){
  			$val = rawurldecode($val) ;
  		}
     $data = array('hostel'=>$info[1],
	 'email' =>$info[2],
	 'phone' => $info[3],
	 'password' => $info[4]
	 );
	 
     $this->db->where('kerberos_username=',$info[0]);
     $query = $this->db->update('users',$data);
     return $query;
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
      'kerberos_username'=>$profile[2],
      'hostel'=>$profile[5],
	  'email'=>$profile[4],
	  'phone'=>$profile[3],
	  'password'=>$profile[1]
	);

    //insert values into users table of database
	$db_debug = $this->db->db_debug;
   $this->db->db_debug = false;
	
    if($this->db->insert('users', $data))
     {
		$this->db->db_debug= $db_debug ; 
		$this->db->from('users');
		$this->db->where('kerberos_username=',$profile[2]);
		$this->db->where('password=',$profile[1]);
		$query = $this->db->get();
	    return array('user'=>$query->result()[0],'success'=>'true') ;
		
	 }
	 else
	 {  $this->db->db_debug= $db_debug ; 
	   return array('success'=>"false") ;
	 }
	
	

   }

   function login($username,$password){
     $this->load->database();
     $this->db->distinct();

     //decode all elements of array
     $username = rawurldecode($username);
     $password = rawurldecode($password);

     $this->db->from('users');
     $this->db->where('kerberos_username=',$username);
     $this->db->where('password=',$password);
     $query = $this->db->get();
	 $i= 0 ;
	 foreach($query->result() as  $row) 
         {  $data = array(
			  'name'=>$row->name,
			  'kerberos_username'=>$row->kerberos_username,
			  'hostel'=>$row->hostel,
			  'email'=>$row->email,
			  'phone'=>$row->phone,
			  'password'=>$row->password,
			  
			);
			$i=$i+1 ;
		 }
	    echo $i ;
		if($i==1)	
		{  $data2=  array(
			   'user'=> $data, 
			   'success' => 'true'
			);
		  return $data2 ;
		
		}
		else
		{
		  $data = array(
			   'success'=>'false'
			);
			return $data ;
		}
	 
     
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
   
   function getadmincomplaints($type){
     $this->load->database();
	 
     $this->db->where('admin=',$type);
     $query = $this->db->get('complaints');
     
	 return $query->result();
   } 
   

}
