<?php
defined('BASEPATH') or exit();

class Call extends CI_Controller{
	function __construct(){
		parent::__construct();	
		
		$this->load->model('Mymodel','m');
		$this->load->helpers('form');
		$this->load->helpers('url');
		
		if($this->session->userdata('level') != 'admin'){
			redirect('auth');
		}
        $this->user_id = $this->session->userdata('user_id');
	}
	
	function index(){
		
		
		$data['title'] = "Contact";
        $data['phone'] = $this->phone();
		
        $this->template->load('template','admin/call',$data);
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
	
	function cobaQuery($params = array()){
		
		$data = array();
		$commands = $this->db->select('*')->from('call_logs');

        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('call_logs_date',$params['search']['sortBy']);
        }else{
            $this->db->order_by('call_logs_date','desc');
        }

        if(!empty($params['search']['keywords'])){
            $this->db->like('phone_number',$params['search']['keywords']);
        }

        if(!empty($params['search']['phoneBy'])){
            $this->db->like('phone_id',$params['search']['phoneBy']);
        }


		
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $commands = $commands->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $commands = $commands->limit($params['limit']);
        }
		$commands = $commands->get();
		
		foreach ($commands->result_array() as $row){
			$baris['call_logs_id'] = $row['call_logs_id'];
			$baris['phone_number'] = $row['phone_number'];
			$baris['call_date'] = $row['call_date'];
            $baris['call_type'] = $row['call_type'];
            $baris['call_duration'] = $row['call_duration'];
			$baris['user_id'] = $row['uid'];
			$baris['phone_id'] = $row['phone_id'];

            $phone = $this->db->get_where('phone', array('phone_id'=> $row['phone_id']) )->result();

            $baris['phone_name'] = '';
            $baris['phone_serial'] = '';
            $baris['phone_model'] = '';
            foreach($phone as $b){
                $baris['phone_name'] = $b->phone_name;
                $baris['phone_serial'] = $b->phone_serial;
                $baris['phone_model'] = $b->phone_model;
            }
			
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
        $config['base_url']    = base_url().'call/ajaxPaginationData';
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
		
		$this->m->hapusbyid(array('call_logs_id'=>$id),'call_logs');
		
	}
	
}
?>