<?php
// include 'resize_img.php';
@session_start();

if(!isset($_SESSION['user'])) 
{
	header("Content-Type: text/html; charset='utf-8'");
	die('Bạn không có quyền truy cập thư mục này');
}

/*

How to modify:
When you create a FileUploader Object, specify it which file types are allowed, maximum file size upload and upload directory like this:
	$fileUploader = new FileUploader('jpg, jpeg, gif, png, zip, rar', 3, 'upload/');
File types are and seperated by commas (,), default are 'jpg, jpeg, gif, png, zip, rar'
Maximum file size upload is in MB, default is: 3 MB
Upload directory is ended with trailing slash (/), default is: 'upload/'

If one or more of these arguments is missed, default value is used in stead of. You can leave if blank if you don't know how to change.
*/
class FileUploader {
	private $fileTypesAllowed;
	private $fileSizeMax;
	private $uploadDir;
	private $files;
	
	/*
	Function: Create FileUploader object
	Arguments:
		$fileTypesAllowed: types of files allowed, separated by commas(,)
		$fileSizeMax: max size of file upload in MB
		$uploadDir: path to directory where files go to, ended with trailing slash (/)
			function __construct($fileTypesAllowed = 'jpg, jpeg, gif, png, zip, rar, mpg, avi, flv, wmv',
	*/

 // script để thực hiện quá trình upload
	
	function __construct($fileTypesAllowed = 'jpg, jpeg, gif, png', $fileSizeMax = 3, $uploadDir = 'uploads/logos') {
		$this->fileTypesAllowed = strtolower($fileTypesAllowed);
		$this->fileSizeMax = $fileSizeMax;
		$this->uploadDir = $uploadDir;
	}
	/*
	Function: Show form upload
	*/
	function showFormUpload() {
		$url = htmlentities($_SERVER['PHP_SELF']);

		echo '
		<script type="text/javascript">
			function addFile() {
				var br = document.createElement("br")
				var newInput = document.createElement("input");
				newInput.name = "filesUpload[]";
				newInput.type = "file";
				document.getElementById("list").appendChild(br);
				document.getElementById("list").appendChild(newInput);
			}
			function wait() {
				document.getElementById("wait").innerHTML = "Please wait while uploading...";
			}
		</script>
		<form action="'.$url.'" method="post" enctype="multipart/form-data" onsubmit="wait()">
			<input type="hidden" name="MAX_FILE_SIZE" value="'.($this->fileSizeMax * 1024 * 1024).'">
			<table cellspacing="0" cellpadding="5" style=" margin:10px auto;">
				<tr><td>Dung lương tối đa</td><td><b>'.$this->fileSizeMax.' MB</b></td></tr>
				<tr><td>Kiểu file</td><td><b>'.$this->fileTypesAllowed.'</b></td></tr>
				<tr><td>Chọn file upload<br><!--<a href="javascript:void(0)" onclick="addFile()">Add more files</a>--></td><td id="list"><input name="filesUpload[]" type="file"></td></tr>
			</table>
			<input type="submit" name="submit" value="Upload File">
		</form>
		<div id="wait"></div>
		
		';
	}
	/*
	Function: Get file upload and set file name and file type
	*/
	function getFilesUpload($files) {
		$j = 0;
		$this->files = array();
		for ($i = 0; $i < count($files['name']); $i++) {
			if (trim($files['name'][$i]) != '') {
				$this->files[$j]['name'] = trim($files['name'][$i]);
				$this->files[$j]['tmp_name'] = $files['tmp_name'][$i];
				$this->files[$j]['error'] = $files['error'][$i];
                $this->files[$j]['type'] = $files['type'][$i];
				$this->files[$j]['ext'] = strtolower(substr($this->files[$j]['name'], strrpos($this->files[$j]['name'], '.') + 1));

				// $this->files[$j]['newName'] = $this->uploadDir.md5_file($this->files[$j]['tmp_name']).'.'.$this->files[$j]['ext'];
				// $this->files[$j]['newName'] = $this->uploadDir . $this->files[$j]['name'] . '.' . $this->files[$j]['ext'];
				// $j++;

// $name_arr = explode('.', $this->files[$j]['name']);
				// $name = $name_arr[0];

                //------------------------------------------------------------------
                $str = $this->files[$j]['name'];
                $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
                $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
                $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
                $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
                $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
                $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
                $str = preg_replace("/(đ)/", 'd', $str);    
                $str = html_entity_decode ($str);
                $str = str_replace(array(' ','_'), '-', $str); 
                $str = html_entity_decode ($str);
                $str = str_replace("ç","c",$str);
                $str = str_replace("Ç","C",$str);
                $str = str_replace(" / ","-",$str);
                $str = str_replace("/","-",$str);
                $str = str_replace(" - ","-",$str);
                $str = str_replace("_","-",$str);
                $str = str_replace(" ","-",$str);
                $str = str_replace( "ß", "ss", $str);
                $str = str_replace( "&", "", $str);
                $str = str_replace( "%", "percent", $str);
                $str = str_replace("----","-",$str);
                $str = str_replace("---","-",$str);
                $str = str_replace("--","-",$str);
                //$str = str_replace(".","-",$str);
                $str = str_replace(",","",$str);
                // In đậm
                $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
                $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
                $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
                $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
                $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
                $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
                $str = preg_replace("/(Đ)/", 'D', $str);
                //------------------------------------------------------------------
				$this->files[$j]['newName'] = $this->uploadDir.$str;
				$j++;
			}
		}
	}
	/*
	Function: Get total files upload
	*/
	function getTotalFiles() {
		return count($this->files);
	}
	/*
	Function: Check error while uploading
	*/
	function checkUploadProcess($i) {
		switch ($this->files[$i]['error']) {
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				echo '<div id="error">File <u>'.$this->files[$i]['name'].'</u> Lớn quá hãy chọn lại file khác</div>';
				return false;
			case UPLOAD_ERR_PARTIAL:
				echo '<div id="error">Lỗi khi tải file <u>'.$this->files[$i]['name'].'</u> , Xin hãy tải lại</div>';
				return false;
		}
		return true;
	}
	/*
	Function: Check file type
	*/
	function checkFileType($i) {
		if (strpos($this->fileTypesAllowed, $this->files[$i]['ext']) === false) {
	//	echo $this->fileTypesAllowed;
			echo '<div id="error">Định dạng không đúng <u>'.$this->files[$i]['name'].'</u> Hãy chọn lại</div>';
			return false;
		}

		return true;
	}
	/*
	Function: Move file to destination folder
	*/
	function moveFile($i) 
	{		
		$image_info = getimagesize($this->files[$i]['tmp_name']);
		
		// if($image_info[0]>500)
		// {
		// 	$image = new SimpleImage();
		//    	$image->load($this->files[$i]['tmp_name']);
		//    	$image->resizeToWidth(500);
		//    	$image->save($this->files[$i]['tmp_name']);
		// }

		if (!@file_exists($this->files[$i]['newName'])) {
			if (!@move_uploaded_file($this->files[$i]['tmp_name'], $this->files[$i]['newName'])) {
				echo '<div id="error">Không thể up file <u>'.$this->files[$i]['name'].'</u> Lên server,Làm lại</div>';
				return false;
			} else {
				   
				echo '<div id="successful">File <u>'.$this->files[$i]['name'].'</u> Hoàn Thành </div>';
			}
		} else {
			echo '<div id="warn">File <u>'.$this->files[$i]['name'].'</u> already exists</div>';
		}
		return true;
	}
	/*
	Function: Return link to uploaded file
	*/
	function getLink($i) {
		$path = dirname($_SERVER['PHP_SELF']);
		if ($path == '' || $path == '/' || $path == '\\') {
			$path = '';
		}

		$link = 'http://'.$_SERVER['SERVER_NAME'].$path.'/'.$this->files[$i]['newName'];
		$link_bs = $this->files[$i]['newName'];
		$link_bs = str_replace("../","",$link_bs);
		$link_bs = 'app/webroot/' . $link_bs;
		$return = "<table><tr><td>Link to file:</td><td><input type=\"text\" onclick=\"this.select()\" value=\"$link\" size=\"50\"></td></tr>";
		if (strpos($this->files[$i]['type'], 'image') !== false) {
			$link = '[img]'.$link.'[/img]';
		} else {
			$link = '[url='.$link.']Download File[/url]';
		}

		$field_id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : 'userfile';

		$return .= "<tr><td>BBCode:</td><td><input type=\"text\" onclick=\"this.select()\" value=\"$link\" size=\"50\"></td></tr></table>";
        $return .= "<tr><td colspan=\"2\"><a href=\"javascript:window.opener.document.getElementById('". $field_id ."').value='$link_bs';window.close();window.history.go(1);window.opener.update_thumb();\">close</a></td> </tr></table>";

		return $return;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>File Uploader</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex, nofollow">
	<style type="text/css">
		body {
			font: normal 13px Arial, Heltical, sans-serif;
			margin: 0;
			text-align: center;
		}
		#page {
			width: 600px;
			margin: 0 auto;
		}
		table {
			font: normal 13px Arial, Heltical, sans-serif;
		}
		h2 {
			font: bold 22px Verdana, Heltical, sans-serif;
			color: #a69;
			margin: 20px;
		}
		#wait, #error, #successful, #warn {
			font-size: 16px;
		}
		#error {
			color: red;
		}
		#successful {
			color: green;
		}
		#warn {
			color: orange;
		}
		#copyright {
			margin-top: 20px;
		}
	</style>
</head>
<body>
 
	<div id="page">
		<center>File đính kèm</center>
		<?php
		$fileUploader = new FileUploader();
		ini_set('post_max_size','100M');
		ini_set('upload_max_filesize','100M');
 		ini_set('max_execution_time','1000');
		ini_set('max_input_time','1000');

		$fileUploader->showFormUpload();
		if (isset($_POST['submit'])) {
			$fileUploader->getFilesUpload($_FILES['filesUpload']);
			if ($fileUploader->getTotalFiles() > 0) {
				for ($i = 0; $i < $fileUploader->getTotalFiles(); $i++) {
					if ($fileUploader->checkUploadProcess($i) && $fileUploader->checkFileType($i) && $fileUploader->moveFile($i)) {
						echo $fileUploader->getLink($i);
					}
				}
			} else {
				echo '<div id="error">Vui lòng chọn File để tải lên.</div>';
			}
		}
		?>
	</div>
</body>
</html>
