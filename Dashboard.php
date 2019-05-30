<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	function upload_imgs() {
		$get_imgs = $this->db->select('temp_images')->where(array('users_id'=>3))->get('users')->row();
		$uploaded_images['uploaded_images'] = json_decode($get_imgs->temp_images);
		// debug($uploaded_images);
		$this->load->view('upload_imgs',$uploaded_images);
	}

	function upload_imgs_formdata() {
		$output['status']=FALSE;

		define('IMAGE_SMALL_DIR', './resources/images/small/');
		define('IMAGE_SMALL_SIZE', 100);
		define('IMAGE_MEDIUM_DIR', './resources/images/medium/');
		define('IMAGE_MEDIUM_SIZE', 750);

		$this->createDir(IMAGE_SMALL_DIR);
		$this->createDir(IMAGE_MEDIUM_DIR);

		if(isset($_FILES['file']['name'])){

			foreach ($_FILES["file"]['name'] as $index => $detail) {

				$path[0] = $_FILES['file']['tmp_name'][$index];
				$file = pathinfo($_FILES['file']['name'][$index]);
				$fileType = $file["extension"];
				$desiredExt='jpg';
				$fileNameNew = rand(333, 999) . time() . ".$desiredExt";
				$path[1] = IMAGE_MEDIUM_DIR . $fileNameNew;
				$path[2] = IMAGE_SMALL_DIR . $fileNameNew;

				if ($this->createThumb($path[0], $path[1], $fileType, IMAGE_MEDIUM_SIZE, IMAGE_MEDIUM_SIZE,IMAGE_MEDIUM_SIZE)) {

					if ($this->createThumb($path[1], $path[2],"$desiredExt", IMAGE_SMALL_SIZE, IMAGE_SMALL_SIZE,IMAGE_SMALL_SIZE)) {
						$output['status']=TRUE;
						$output['image_medium']= $path[1];
						$output['image_small']= $path[2];

						$all_images[] = $fileNameNew;
					}
				}

			}

		}

		$get_imgs = $this->db->select('temp_images')->where(array('users_id'=>3))->get('users')->row();
		$oldimsges = json_decode($get_imgs->temp_images);
		// debug($oldimsges);
		if ($oldimsges != '') {
			foreach ($all_images as $key => $value) {
				array_push($oldimsges, $value);
			}
		} else {
			$oldimsges = $all_images;
		}
		// debug($oldimsges);

		$json_images = json_encode($oldimsges);
		$this->db->where(array('users_id'=>3))->update('users',array('temp_images'=>$json_images));

		// debug($all_images);

	}

	function createDir($path){		
		if (!file_exists($path)) {
			$old_mask = umask(0);
			mkdir($path, 0777, TRUE);
			umask($old_mask);
		}
	}

	function createThumb($path1, $path2, $file_type, $new_w, $new_h, $squareSize = ''){
		/* read the source image */
		$source_image = FALSE;

		if (preg_match("/jpg|JPG|jpeg|JPEG/", $file_type)) {
			$source_image = imagecreatefromjpeg($path1);
		}
		elseif (preg_match("/png|PNG/", $file_type)) {

			if (!$source_image = @imagecreatefrompng($path1)) {
				$source_image = imagecreatefromjpeg($path1);
			}
		}
		elseif (preg_match("/gif|GIF/", $file_type)) {
			$source_image = imagecreatefromgif($path1);
		}		
		if ($source_image == FALSE) {
			$source_image = imagecreatefromjpeg($path1);
		}

		$orig_w = imageSX($source_image);
		$orig_h = imageSY($source_image);

		if ($orig_w < $new_w && $orig_h < $new_h) {
			$desired_width = $orig_w;
			$desired_height = $orig_h;
		} else {
			$scale = min($new_w / $orig_w, $new_h / $orig_h);
			$desired_width = ceil($scale * $orig_w);
			$desired_height = ceil($scale * $orig_h);
		}

		if ($squareSize != '') {
			$desired_width = $desired_height = $squareSize;
		}

		/* create a new, "virtual" image */
		$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
		// for PNG background white----------->
		$kek = imagecolorallocate($virtual_image, 255, 255, 255);
		imagefill($virtual_image, 0, 0, $kek);

		if ($squareSize == '') {
			/* copy source image at a resized size */
			imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $orig_w, $orig_h);
		} else {
			$wm = $orig_w / $squareSize;
			$hm = $orig_h / $squareSize;
			$h_height = $squareSize / 2;
			$w_height = $squareSize / 2;

			if ($orig_w > $orig_h) {
				$adjusted_width = $orig_w / $hm;
				$half_width = $adjusted_width / 2;
				$int_width = $half_width - $w_height;
				imagecopyresampled($virtual_image, $source_image, -$int_width, 0, 0, 0, $adjusted_width, $squareSize, $orig_w, $orig_h);
			}

			elseif (($orig_w <= $orig_h)) {
				$adjusted_height = $orig_h / $wm;
				$half_height = $adjusted_height / 2;
				imagecopyresampled($virtual_image, $source_image, 0,0, 0, 0, $squareSize, $adjusted_height, $orig_w, $orig_h);
			} else {
				imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $squareSize, $squareSize, $orig_w, $orig_h);
			}
		}

		if (@imagejpeg($virtual_image, $path2, 90)) {
			imagedestroy($virtual_image);
			imagedestroy($source_image);
			return TRUE;
		} else {
			return FALSE;
		}
	}	







}








// function upload_imgs_formdata() {
		// $ds          = DIRECTORY_SEPARATOR;  //1

		// $storeFolder = '../resources/images/';   //2

		// if (!empty($_FILES)) {

		//     $tempFile = $_FILES['file']['tmp_name'];          //3             

		//     $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4

		//     $targetFile =  $targetPath. $_FILES['file']['name'];  //5

		//     move_uploaded_file($tempFile,$targetFile); //6

		// }

		// debug($_FILES);

/*
		$config['upload_path']          = './resources/images/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 50000;
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;
		// $config['overwrite']     = FALSE;
		// $config['remove_spaces'] = TRUE;
		// $config['encrypt_name'] = TRUE;

		$this->load->library('upload');
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('file[]')) {
			$error = array('error' => $this->upload->display_errors());
			debug($error);
		} else {
			$icon_image = $this->upload->data();
		}

		debug($icon_image);*/


	// }







	// }
