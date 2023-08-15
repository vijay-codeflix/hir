<?php

Class Login_model extends MY_Model
{
 
 function __construct(){

	 parent::__construct();

 }

  function login($username, $password)
  {
    $this->db-> select('usr.*, ut.type as user_type');
    $this->db-> from('users usr');
    $this->db-> join('user_types ut', 'usr.user_type = ut.id','LEFT');
    $this->db-> where('usr.email', $username);
    $this->db-> where('usr.password', md5($password));
    $this->db-> limit(1);

    $query = $this->db-> get();

    if($query-> num_rows() == 1)
    {
      return $query->result();
    }
    else
    {
      return false;
    }
  }
   
  function changepassword($user)
  {

    $data['password'] = md5($this->input->post('password'));
    $data['modified'] = date('Y-m-d H:i:s');

    $this->db->where('username', $user);
    $query = $this->db->update('users', $data);
      if($query !== 0){

        return true;

      }else{

        return false;

      } 
    
  }


}
?>