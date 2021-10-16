<?php
defined('BASEPATH') or exit();

class Commander extends CI_Controller{
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
		
		
		$data['title'] = "Commander";
        $data['phone'] = $this->phone();
        $data['commands_prompt'] = $this->commands_prompt();
		
        $this->template->load('template','admin/commander',$data);
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
		$commands = $this->db->select('*')->from('commands');

        if(!empty($params['search']['sortBy'])){
            $this->db->order_by('start',$params['search']['sortBy']);
        }else{
            $this->db->order_by('start','desc');
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
			$baris['commands_id'] = $row['commands_id'];	
			$baris['start'] = $row['start'];	
			$baris['end'] = $row['end'];	
			$baris['id'] = $row['id'];
			$baris['param1'] = $row['param1'];	
			$baris['param2'] = $row['param2'];
			$baris['param3'] = $row['param3'];
			$baris['param4'] = $row['param4'];
			$baris['panding'] = (int) $row['panding'];
			$baris['user_id'] = $row['uid'];
			
			$commands_prompt = $this->db->get_where('commands_prompt', array('commands_prompt_id'=> $row['id']) )->result();
		
			$baris['commands_prompt_name'] = '';
			foreach($commands_prompt as $b){	
				$baris['commands_prompt_name'] = $b->commands_prompt_name;	
			}

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
	
	function getRowsUserDetail($params = array()){
        //$this->db->select('user.*,users.user_id,users.display_name,users.user_foto,users.email,users.oauth_uid AS user_uid');
		//$this->db->join('users', 'user.user_id = users.user_id');
		$this->db->select('siswa_id,siswa_nama');
        $this->db->from('siswa');
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach ($params['conditions'] as $key => $value) {
                $this->db->where($key,$value);
            }
        }
        
        //search by terms
        if(!empty($params['searchTerm'])){
			//$this->db->like(array('siswa_nama' => $params['searchTerm'], 'email' => $params['searchTerm']));
            $this->db->like('siswa_nama', $params['searchTerm']);
        }
        
        $this->db->order_by('siswa_nama', 'asc');
        
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;

        //return fetched data
        return $result;
    }
	
	function autocompleteData() {
        $returnData = array();
        
        // Get skills data
        $conditions['searchTerm'] = $this->input->get('term');
        //$conditions['conditions']['status'] = '1';
        $skillData = $this->getRowsUserDetail($conditions);
        
        // Generate array
        if(!empty($skillData)){
            foreach ($skillData as $row){
                $data['id'] = $row['siswa_id'];
                $data['value'] = $row['siswa_nama'];
				
				$data['label'] = '<div onclick="javascript:void(0)" style="padding:5px;"><div style="padding:5px;">'.$row['siswa_nama'].'</div>
				<div style="clear: both;"></div>
				</div>';
                array_push($returnData, $data);
            }
        }
        
        // Return results as json encoded array
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($returnData);
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
        $config['base_url']    = base_url().'commander/ajaxPaginationData';
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
	
	
	function tambahdata(){						

		$id = $this->input->post('id');
        $phone_id = $this->input->post('phone_id');
        $uid = $this->input->post('uid');
        $param1 = $this->input->post('param1');
        $param2 = $this->input->post('param2');
        $param3 = $this->input->post('param3');
        $param4 = $this->input->post('param4');
		$date = date('Y-m-d H:i:s');
		
		if( $id == ""){
			$result['pesan'] = "Perintah Kosong!";
		}else{
			$result['pesan'] = "";
			$data =  array(
				'start' => $date,
				'end' => '',
				'panding' => 1,
				'id' => $id,
                'param1' => $param1,
                'param2' => $param2,
                'param3' => $param3,
                'param4' => $param4,
                'uid' => $uid,
                'phone_id' => $phone_id
			);
			$this->db->insert('commands',$data);
			$id = $this->db->insert_id();
			
		}
		
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	
	function hapusdatabyid(){
		$id = $this->input->post('id');	
		
		$this->m->hapusbyid(array('commands_id'=>$id),'commands');
		
	}
	
}
?>