<?php
defined('BASEPATH') or exit();

class Phone extends CI_Controller{
	function __construct(){
		parent::__construct();	
		
		$this->load->model('Mymodel','m');
		$this->load->helpers('form');
		$this->load->helpers('url');
		
		if($this->session->userdata('level') != 'admin'){
			redirect('auth/profile');
		}
        $this->user_id = $this->session->userdata('user_id');
	}
	
	function index(){
		
		
		$data['title'] = "Phone";
        $data['phone'] = $this->phone();
        $data['commands_prompt'] = $this->commands_prompt();
		
        $this->template->load('template','admin/phone',$data);
	}


    function phone(){

        $this->db->select('*');
        $this->db->from('phone');
        $this->db->order_by('phone_serial','asc');
        $phone = $this->db->get();

        $items = array();

        foreach ($phone->result_array() as $row1){
            $data['id'] = $row1['phone_id'];
            $data['title'] = strtoupper( !empty($row1['phone_name']) ? $row1['phone_name'] : "Unknown" ." - ". $row1['phone_model'] );

            array_push($items, $data);
        }

        return $items;
    }

    function commands_prompt(){

        $this->db->select('*');
        $this->db->from('commands_prompt');
        $this->db->order_by('commands_prompt_name','asc');
        $commands_prompt = $this->db->get();

        $items = array();

        foreach ($commands_prompt->result_array() as $row1){
            $data['id'] = $row1['commands_prompt_id'];
            $data['title'] = $row1['commands_prompt_name'];

            array_push($items, $data);
        }

        return $items;
    }

	function cobaQuery($params = array()){
		
		$data = array();
		$commands = $this->db->select('*')->from('phone');
		//$commands = $commands->join('collect_phone_details', 'collect_phone_details.phone_id = phone.phone_id');

        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('phone_last_active',$params['search']['sortBy']);
        }else{
            $this->db->order_by('phone_last_active','desc');
        }

        if(!empty($params['search']['phoneBy'])){
            $this->db->like('phone_id',$params['search']['phoneBy']);
        }

        if(!empty($params['search']['keywords'])){
            $this->db->like('phone_name',$params['search']['keywords']);
            $this->db->or_like('phone_serial',$params['search']['keywords']);
            $this->db->or_like('phone_model',$params['search']['keywords']);
        }

		
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $commands = $commands->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $commands = $commands->limit($params['limit']);
        }
		$commands = $commands->get();
		
		foreach ($commands->result_array() as $row){

		    $phone_detail = $this->db->get_where("collect_phone_details",array(
		        'k'=>"VERSION.RELEASE",
                'phone_id'=> $row['phone_id']
            ))->result();

            $versioscodename = "";
		    foreach ($phone_detail as $v){
                $versioscodename = $v->v;
            }

			$baris['phone_id'] = $row['phone_id'];
			$baris['phone_name'] = $row['phone_name'];
			$baris['phone_last_active'] = $row['phone_last_active'];
			$baris['versicode'] = $row['versicode'];
			$baris['versiname'] = $row['versiname'];
            $baris['versioscodename'] = $versioscodename;
            $baris['phone_serial'] = $row['phone_serial'];
            $baris['phone_model'] = $row['phone_model'];
            $baris['uid'] = $row['uid'];
            $baris['ForceUpload'] = $row['ForceUpload'];

            $status = "offline";
            if(strtotime($row['phone_last_active']) > strtotime("-10 minute")) { //jika waktu user lebih dari 10menit
                $status = "online";
            }

            $baris['status'] = $status;
			
			array_push($data, $baris);
		}
		
		return $data;
		//$this->output->set_header('Content-Type: application/json; charset=utf-8');
		//echo json_encode($data);
	}
	
	function ajaxPaginationData(){
		
        $this->perPage = 10;
        $conditions = array();
        
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        $levelBy = $this->input->post('levelBy');
        $limitBy = $this->input->post('limitBy');
        $phoneBy = $this->input->post('phoneBy');
	
		
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        if(!empty($levelBy)){
            $conditions['search']['levelBy'] = $levelBy;
        }
        if(!empty($phoneBy)){
            $conditions['search']['phoneBy'] = $phoneBy;
        }
        if(!empty($limitBy)){
            $this->perPage = (int) $limitBy;
        }
        
		
        //total rows count
        $totalRec = count($this->cobaQuery($conditions));
        
        //pagination configuration
        $config['target']      = '#postList tbody';
        $config['base_url']    = base_url().'phone/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
		
		
		// integrate bootstrap pagination
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Prev';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['empData'] = $this->cobaQuery($conditions);
		$data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data['pagination'] = $this->ajax_pagination->create_links();
        
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);	
    }

	
	function hapusdatabyid(){
		$id = $this->input->post('id');

        $camera = $this->db->get_where('phone', array('phone_id'=> $id) )->result();
        foreach ($camera as $a){

            if( file_exists("uploads/".$a->image )){
                unlink( "uploads/".$a->image );
            }
        }
		
		$this->m->hapusbyid(array('phone_id'=>$id),'phone');
		
	}

    function republish(){
        $id = $this->input->post('id');
        $phone = $this->db->select('*')->from('phone')->where('phone_id', $id )->get()->result();

        foreach ($phone as $a) {
            $ForceUpload = $a->ForceUpload;

            $ForceUploadX = 1;
            if($ForceUpload > 0){
                $ForceUploadX = 0;
            }
            $this->m->simpanbyid(array('ForceUpload'=>$ForceUploadX),array('phone_id'=>$id),'phone');
        }
        //

        $result['pesan'] = $phone;
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode( $result );
    }



    function republishall(){

        $this->db->where(array(
            "ForceUpload" => 0
        ));
        $this->db->update("phone",array("ForceUpload" => 1));

        $result['pesan'] = "ok";
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode( $result );
    }


    function unpublishall(){

        $this->db->where(array(
            "ForceUpload" => 1
        ));
        $this->db->update("phone",array("ForceUpload" => 0));

        $result['pesan'] = "ok";
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode( $result );
    }
}
?>