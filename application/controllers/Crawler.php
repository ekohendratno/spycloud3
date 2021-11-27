<?php
defined('BASEPATH') or exit();

class Crawler extends CI_Controller{
	function __construct(){
		parent::__construct();	
		
	}
	
	
	
	function index(){

        $this->listFolderFiles('uploads');

    }


    function listFolderFiles($dir){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        echo '<ol>';
        foreach($ffs as $ff){
            $link = "#";

            if(!is_dir($dir.'/'.$ff)){
                $link = base_url() . 'thumb.php?src=./'.$dir.'/'.$ff.'';
            }

            echo '<li><a href="'.$link.'">'.$ff;
            if(is_dir($dir.'/'.$ff)) $this->listFolderFiles($dir.'/'.$ff);
            echo '</a></li>';
            sleep(0.1);
        }
        echo '</ol>';
    }

	
}