<?php
defined('BASEPATH') or exit();

class Files extends CI_Controller{
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
		
		
		$data['title'] = "Files";
        $this->template->load('template','admin/files',$data);
	}

	function buka($path){
        //$dir    = './uploads/';

        $data['title'] = "Files ".$path;
        $data['path'] = $path;
        $this->template->load('template','admin/files_detail',$data);
    }
    
    function hapusdatabypath(){
        $path = $this->input->post("path");
        $file = './uploads' . $path;
        if (file_exists($file)) {
            unlink($file);
        }
    }


    function ajaxPaginationDataDir(){

	    $limit = 10;
        $page = $this->input->get('page');
        $sortBy = $this->input->get('sortBy');
        $limitBy = $this->input->get('limitBy');

        $dir    = './uploads/';
        $list = $this->getDirContents($dir);

        $data = array();

        $no = 1;
        foreach($list as $v){
                $dd = array();
                $dd['id'] = $no;
                $dd['device'] = $v['a'];
                $dd['jenis'] = $v['b'];
                $dd['folder'] = $v['c'];
                $dd['link'] = base_url("admin/files/buka/") . $v['c'];
                $dd['tanggal'] = $v['d'];
                $dd['path'] = $v['e'];

                
                array_push($data,$dd);


            $no++;
        }

        $order = array('tanggal' => 'desc');
        usort($data, function ($a, $b) use ($order) {
            $t = array(true => -1, false => 1);
            $r = true;
            $k = 1;
            foreach ($order as $key => $value) {
                $k = ($value === 'asc') ? 1 : -1;
                $r = ($a[$key] < $b[$key]);
                if ($a[$key] !== $b[$key]) {
                    return $t[$r] * $k;
                }

            }
            return $t[$r] * $k;
        });


        $page = ! empty( $page ) ? (int) $page : 1;
        $total = count( $data ); //total items in array
        $limit = ! empty( $limitBy ) ? (int) $limitBy : $limit; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
        $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;

        $data = array_slice( $data, $offset, $limit );






        //total rows count
        $totalRec = $total;

        //pagination configuration
        $config['target']      = '#postList tbody';
        $config['base_url']    = base_url().'files/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $page;
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
        $conditions['limit'] = $page;

        //get posts data
        $a['empData'] = $data;
        $a['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $a['pagination'] = $this->ajax_pagination->create_links();

        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        echo json_encode($a);
    }

    function ajaxPaginationData(){

        $limit = 10;
        $page = $this->input->post('page');
        $sortBy = $this->input->post('sortBy');
        $limitBy = $this->input->post('limitBy');
        $pathBy = $this->input->post('pathBy');

        $dir    = './uploads/'.$pathBy."/";
        $list = $this->getFilesDirContents($dir);

        $data = array();

        $no = 1;
        foreach((array)$list as $v){
            
            $dd = array();
            $dd['id'] = $no;
            $dd['device'] = $v['a'];
            $dd['image'] = $v['c'];
            $dd['jenis'] = $v['b'];
            $dd['link'] = base_url("uploads") . $v['d'];
            $dd['tanggal'] = $v['e'];
            $dd['tanggal2'] = date ("F d Y H:i:s",$v['e']); 
            $dd['path'] = $v['d'];
            $dd['size'] = $this->human_filesize($v['f']);
            $dd['status'] = $v['g'];
            $dd['ext'] = $v['h'];



            $pp    = './uploads'.$v['d'];
            //if($this->cekFile($pp) === false){
                //$dd['status'] = false;
            //}else{
                //$dd['status'] = true;
            //}
                
                
            array_push($data,$dd);


            $no++;
            
        }


        $page = ! empty( $page ) ? (int) $page : 1;
        $total = count( $data ); //total items in array
        $limit = ! empty( $limitBy ) ? (int) $limitBy : $limit; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
        $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;

        $data = array_slice( $data, $offset, $limit );






        //total rows count
        $totalRec = $total;

        //pagination configuration
        $config['target']      = '#postList tbody';
        $config['base_url']    = base_url().'files/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $page;
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
        $conditions['limit'] = $page;

        //get posts data
        $a['empData'] = $data;
        $a['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $a['pagination'] = $this->ajax_pagination->create_links();

        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        echo json_encode($a);
    }

    function getDirContents($dir,&$results = array()) {
        $scanned_directory = array_diff(scandir($dir), array('..', '.'));

        foreach ($scanned_directory as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (is_dir($path)) {
                $ff = explode("uploads" ,$path);
                $fa = str_replace("\\", '/', $ff[1]);
                $xa = explode("/" ,$fa);
                $xc = explode("_" ,$xa[1]);

                $a = array();
                $a['a'] = !empty($xc[0]) ? $xc[0] : "";
                $a['b'] = !empty($xc[1]) ? $xc[1] : "";
                $a['c'] = !empty($xa[1]) ? $xa[1] : "";
                $a['d'] = strtotime( date ("F d Y H:i:s", filemtime($path)) );
                $a['e'] = !empty($xa[1]) ? $xa[1] : "";


                $results[] = $a;
            }
        }

        //$this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        //echo json_encode($results);
        return $results;
    }

    function getFilesDirContents( $dir, &$results = array() ) {
        //$dir = './uploads/21312312323_fotos/';
        if(!is_dir($dir)) return;

        $files = scandir($dir);

        foreach ((array)$files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $ff = explode("uploads" ,$path);
                $fa = str_replace("\\", '/', $ff[1]);

                $xa = explode("/" ,$fa);
                $xc = explode("_" ,$xa[1]);

                $ext = pathinfo($path, PATHINFO_EXTENSION);

                $a = array();
                $a['a'] = !empty($xc[0]) ? $xc[0] : "";
                $a['b'] = !empty($xc[1]) ? $xc[1] : "";
                $a['c'] = !empty($xa[2]) ? $xa[2] : "";
                $a['d'] = !empty($fa) ? $fa : "";
                $a['e'] = filemtime($path);
                $a['f'] = filesize($path);
                $a['g'] = "";//$this->cekFile2($path);
                $a['h'] = $ext;


                //if(getimagesize($path) === false){
                //}else{
                    $results[] = $a;
                //}
            } else if ($value != "." && $value != "..") {
                $this->getFilesDirContents($path, $results);
                //$results[] = $path;
            }
        }
        
        

        $order = array('e' => 'desc');
        usort($results, function ($a, $b) use ($order) {
            $t = array(true => -1, false => 1);
            $r = true;
            $k = 1;
            foreach ($order as $key => $value) {
                $k = ($value === 'asc') ? 1 : -1;
                $r = ($a[$key] < $b[$key]);
                if ($a[$key] !== $b[$key]) {
                    return $t[$r] * $k;
                }

            }
            return $t[$r] * $k;
        });

        //$this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        //echo json_encode($results);
        return $results;
    }
	
	
	function cekFile($file){

        $img = imagecreatefromjpeg($file);
        
        $imagew = imagesx($img);
        $imageh = imagesy($img);
        $xy = array();
        
        $last_height = $imageh - 5;
        
        $foo = array();
        
        $x = 0;
        $y = 0;
        for ($x = 0; $x <= $imagew; $x++) 
        {
            for ($y = $last_height;$y <= $imageh; $y++ ) 
            {
                $rgb = @imagecolorat($img, $x, $y);
        
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
        
                if ($r != 0)
                {
                    $foo[] = $r;
                }
            }
        }
        
        $bar = array_count_values($foo);
        
        $gray = (isset($bar['127']) ? $bar['127'] : 0) + (isset($bar['128']) ? $bar['128'] : 0) + (isset($bar['129']) ? $bar['129'] : 0);
        $total = count($foo);
        $other = $total - $gray;
        
        if ($gray > $other)
        {
            return false;
        }
        else
        {   
            return true;
        }
	}
	
	
	
	function human_filesize($bytes, $decimals = 2) {
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor > 0) $sz = 'KMGT';
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
    }
}
?>