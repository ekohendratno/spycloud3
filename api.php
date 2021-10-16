<?php
error_reporting(0);

$mysql_host = '';
$mysql_user = '';
$mysql_pass = '';
$mysql_database = '';



$user_id = $_GET['user_id'];
$versicode = $_GET['versicode'];
$versiname = $_GET['versiname'];

$data = array();
$data["success"] = false;
$data['response'] = array();

if(!empty($user_id)){

    if($_GET['update'] == 1){

        $dd = array();
        $dd['lastversioncode'] = 24;
        $dd['lastversionname'] = '1.1';

        $data["success"] = true;
        array_push($data['response'],$dd);
    }elseif(isset($_GET['status'])){
        //save data status phone
        $data["success"] = true;

        $dd = array();
        $dd['ForceWifiOnForRecordUpload'] = false;

        array_push($data['response'],$dd);
    }

    header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
    echo json_encode($data);

}else{

    header('Content-Type: application/json; charset=utf-8,Access-Control-Allow-Origin: *');
    echo json_encode($data);
}