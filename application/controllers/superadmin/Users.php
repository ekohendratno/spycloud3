<?php
defined('BASEPATH') or exit();

class Users extends CI_Controller{
	function __construct(){
		parent::__construct();	
		
		$this->load->model('Mymodel','m');
		$this->load->helpers('form');
		$this->load->helpers('url');
		
		if($this->session->userdata('level') != 'admin'){
			redirect('auth/profile');
		}
	}
	
	function index(){
		
		
		$data['title'] = "Data Users";
		$data['commanders'] = $this->commanders();
		
        $this->template->load('template','admin/users',$data);
	}
	
	
	
	function users(){
		
		$this->db->select('*');
		$this->db->from('users');
		$this->db->group_by('username','asc');
		$users = $this->db->get();
		
		$items = array();
		foreach ($users->result_array() as $row1){
			$data['id'] = $row1['user_id'];
			$data['title'] = strtoupper( $row1['username'] );
				
			array_push($items, $data);
		}
		
		return $items;
	}
	
	function commanders(){
		
		$this->db->select('*');
		$this->db->from('commands_prompt');
		$this->db->group_by('commands_prompt_name','asc');
		$users = $this->db->get();
		
		$items = array();
		foreach ($users->result_array() as $row1){
			$data['id'] = $row1['commands_prompt_id'];
			$data['title'] = strtoupper( $row1['commands_prompt_name'] );
				
			array_push($items, $data);
		}
		
		return $items;
	}
	
	function cobaQuery($params = array()){
		
		$data = array();
		$users = $this->db->select('*')->from('users');
		
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $users = $users->like('username',$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $users = $users->order_by('username',$params['search']['sortBy']);
        }else{
            $users = $users->order_by('username','asc');
        }
		
        //filter data by searched keywords
        if(!empty($params['search']['levelBy'])){
            $users = $users->where('level',$params['search']['levelBy']);
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $users = $users->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $users = $users->limit($params['limit']);
        }
		$users = $users->get();
		
		foreach ($users->result_array() as $row){
			$baris['user_id'] = $row['user_id'];	
			$baris['username'] = $row['username'];	
			$baris['password'] = $row['password'];	
			$baris['email'] = $row['email'];
			$baris['level'] = $row['level'];
			
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
	
		
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        if(!empty($levelBy)){
            $conditions['search']['levelBy'] = $levelBy;
        }
        if(!empty($limitBy)){
            $this->perPage = (int) $limitBy;
        }
        
		
        //total rows count
        $totalRec = count($this->cobaQuery($conditions));
        
        //pagination configuration
        $config['target']      = '#postList tbody';
        $config['base_url']    = base_url().'users/ajaxPaginationData';
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
	
	function ambildatabyid(){		
		$id = $this->input->post('id');
		$users = $this->m->ambilbyid(array('user_id'=>$id),'users');
		
		
		$baris = array();
		
		foreach ($users->result_array() as $row){
			$baris['user_id'] = $row['user_id'];	
			$baris['username'] = $row['username'];	
			$baris['password'] = $row['password'];	
			$baris['email'] = $row['email'];
			
			$baris['level'] = $row['level'];
			$baris['last_active'] = $row['last_active'];
			

		}
		
		
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($baris);
	}
	
	
	function tambahdata(){						
		
		//$user = $this->session->userdata('userData');
		$nama = $this->input->post('nama');
		$nomorinduk = $this->input->post('nomorinduk');
		$jk = $this->input->post('jk');
		$jurusan_id = $this->input->post('jurusan_id');
		$user_id = $this->input->post('user_id');
		
		if( $nama == ""){
			$result['pesan'] = "Nama user Kosong!";
		}elseif( $user_nis == ""){
			$result['pesan'] = "NIS Kosong!";
		}else{
			$result['pesan'] = "";
			$data =  array(
				'user_nama' => $user_nama,
				'user_nis' => $user_nis,
				'user_jk' => $user_jk,
				'jurusan_id' => $jurusan_id
			);
			$this->db->insert('users',$data);
			$id = $this->db->insert_id();
			//$id = $this->db->result_id();
			
			if($id != '' && $user_id != ''){
				$this->m->simpanbyid(array('user_id'=>$id),array('id'=>$user_id),'users');
			}
		}
		
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	
	function simpandatabyid(){
		$id = $this->input->post('id');
		$siswa_id = $this->input->post('siswa_id');
		
		if( $siswa_id == ""){
			$result['pesan'] = "Siswa Belum di hubungkan!";
		}else{
			$result['pesan'] = "";
			$this->m->simpanbyid(array('siswa_id'=>$siswa_id),array('id'=>$id),'users');
		}
		
		
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result);
	}
	
	function hapusdatabyid(){
		$id = $this->input->post('id');	
		
		$this->m->hapusbyid(array('user_id'=>$id),'users');		
		
	}
	
}
?>