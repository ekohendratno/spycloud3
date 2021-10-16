<?php  
class Dashboard extends CI_Controller{
    
    
    function index(){
		$data['title'] = 'Dashboard Admin';
        $this->template->load('template','admin/dashboard',$data);
		
		if($this->session->userdata('level') != 'admin'){
			redirect('auth/profile');
		}
    }
}
?>