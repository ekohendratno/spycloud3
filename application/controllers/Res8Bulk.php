<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Res8Bulk extends CI_Controller {
    function __construct() {
        parent::__construct();

    }

    function index(){

		$img_dir = $this->input->get("path");


		if(!empty($img_dir)){
			$img_dir = FCPATH . "uploads/".$img_dir."/";



			ini_set('memory_limit', '-1');
			ini_set('max_execution_time', 300);

			echo 'Bulk Image Resizing in PHP - Starting...';



			//echo $img_dir;
			$scanned_dir = array_diff(scandir($img_dir), array('..', '.'));
			//print_r($scanned_dir);

			foreach($scanned_dir as $filename) {
				//echo $filename;
				//echo nl2br("\n");
				try {
					// code



					// Get new sizes
					list($width, $height, $type) = getimagesize($img_dir . $filename);

					$newwidth = 1;
					$newheight = 1;

					// Load
					$thumb = imagecreatetruecolor($newwidth, $newheight);

					switch ($type) {
						case 1:
							$source = imagecreatefromgif($img_dir. $filename);

							// Resize
							imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagegif($thumb, $img_dir. $filename);
							imagedestroy($thumb);
							break;
						case 2:
							$source = imagecreatefromjpeg($img_dir. $filename);

							// Resize
							imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagejpeg($thumb, $img_dir. $filename, 1);
							imagedestroy($thumb);
							break;
						case 3:
							$source = imagecreatefrompng($img_dir. $filename);

							// Resize
							imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagepng($thumb, $img_dir. $filename);
							imagedestroy($thumb);
							break;
						case 18:
							$source = imagecreatefromwebp($img_dir. $filename);

							// Resize
							imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
							imagewebp($thumb, $img_dir. $filename);
							imagedestroy($thumb);
							break;
					}







					// if something is not as expected
					// throw exception using the "throw" keyword

					// code, it won't be executed if the above exception is thrown
				} catch (Exception $e) {
					// exception is raised and it'll be handled here
					// $e->getMessage() contains the error message
				}




			}

			echo nl2br("\n");
			echo nl2br("\n");

			echo 'Bulk Image Resizing in PHP - Finished';
		}
	}

}
?>
