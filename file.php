<?php
public function FindFile($name, $where){
	$files = glob("../".$where."/*".$name."*.*", GLOB_ERR);
	try{
		if(count($files) > 0){
			foreach($files as $file){
				$info = pathinfo($file);
				$name = explode('* ', basename($file));
			}
		}
	}


}



?>