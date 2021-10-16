<?php

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
		
    }

    function index(){		
		
		$level = $this->session->userdata('level');
		if ( $level == 'superadmin' ) redirect('superadmin/dashboard');
		elseif ( $level == 'admin' ) redirect('admin/dashboard');
		else $this->load->view('auth/login');
		
    }
	
	function profile(){
		$this->load->view('auth/profile');
	}
	
	function signin(){
		$username = $this->input->post('username');
		$password = $this->input->post('password');
			
		$data = array();
		if(empty($username) || empty($password)){			
			$data['pesan'] = '<div class="alert alert-danger" role="alert"><strong>Maaf!</strong> Username dan Password kosong!</div>';
		}else{		
		
			$this->db->where(array(
					'username'=> $username,
					'password'=> $password
			));

			$users = $this->db->get('users')->row_array();		
            $users = $this->_getUsersDetail($users);

			if ( !empty($users) && $users['level'] == 'superadmin' ) {
				$this->session->set_userdata($users);
				$data['pesan'] = '';
				$data['redirect'] = 'superadmin/dashboard';
			}elseif ( !empty($users) && $users['level'] == 'admin' ) {
				$this->session->set_userdata($users);
				$data['pesan'] = '';
				$data['redirect'] = 'admin/dashboard';
			}else{
				$data['pesan'] = '<div class="alert alert-danger" role="alert"><strong>Maaf!</strong> Username dan Password tidak sesuai!</div>';
				$data['redirect'] = 'auth';
			}
		}
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);	
		
	}
	
	function _getUsersDetail($users){
		$baris = array();
		$baris['user_id'] = $users['user_id'];
		$baris['username'] = $users['username'];
		$baris['password'] = $users['password'];
		$baris['email'] = $users['email'];
		$baris['level'] = $users['level'];
		$baris['last_active'] = $users['last_active'];
		$baris['foto'] = base_url('img/avatar.png');
			
		
		return $baris;
	}

    function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}