<?php  
class Dashboard extends CI_Controller{
    
    
    function index(){
		$data['title'] = 'Dashboard Admin';
        $this->template->load('template','superadmin/dashboard',$data);
		
		if($this->session->userdata('level') != 'superadmin'){
			redirect('auth/profile');
		}
    }
}
?>