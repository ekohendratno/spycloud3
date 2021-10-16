<?php
defined('BASEPATH') or exit();

class Mymodel extends CI_Model{
	
	
	function getdata($tabel){
		return $this->db->get($tabel)->result();
	}
	
	function tambahdata($data,$tabel){
		$this->db->insert($tabel,$data);
	}
	
	function ambilbyid($where,$tabel){
		return $this->db->get_where($tabel,$where);
	}
	
	function simpanbyid($data,$where,$tabel){
		$this->db->where($where);
		$this->db->update($tabel,$data);
	}
	
	function hapusbyid($where,$tabel){
		$this->db->where($where);		
		$this->db->delete($tabel);
	}
	
	function getRows($params = array(),$args){
        $this->db->select('*');
        $this->db->from($args['tabel']);
        //filter data by searched keywords
        if(!empty($params['search']['keywords'])){
            $this->db->like($args['sortByTitle'],$params['search']['keywords']);
        }
        //sort data by ascending or desceding order
        if(!empty($params['search']['sortBy'])){
            $this->db->order_by($args['sortByTitle'],$params['search']['sortBy']);
        }else{
            $this->db->order_by($args['orderby'],'desc');
        }
        //set start and limit
        if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit']);
        }
        //get records
        $query = $this->db->get();
        //return fetched data
        return ($query->num_rows() > 0)?$query->result_array():FALSE;
    }
	
	
	
	function split_name($name) {
		
		$name = strtolower($name);
		$name = str_replace('.',' ',$name);
		$name = str_replace('  ',' ',$name);
		$coma = explode(',',$name);
		
		$a = 'bukusaku';
		if( count($coma) > 0 ){
			$parts = explode(' ',$coma[0]);		
			$a = $coma[0];
			if( count($parts) > 0 ){
				$a = $parts[0];	
				if( count($parts) > 1 ){
					$a = $a.$parts[1];	
				}
			}
		}

		return $a;
	}
	
	function generatateuser( $string, $mode = 0 ){
		
		$string = $this->split_name($string);	
		$string = strtolower($string);
		
		if($mode == 1){
			$nrRand = rand(10, 99);
		}else{
			$nrRand = rand(1000, 9999);
		}

		return trim($string).trim($nrRand);
	}
}

?>