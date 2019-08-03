<?php
	class PMCommon{
		//Load lib components
		public static function load_lib_components($folder = '', $file = '', $type = ''){
			$url =  _POKA_PLUGIN_COMPONENT_URL_ . $folder . $type . '/' . $file;
			
			if($type == 'css'){
				$name = str_replace(".min.css", "", $file) . '-css';
				wp_enqueue_style('poka_load_' . $name, $url);
			}
			
			if($type == 'js'){
				$name = str_replace(".js", "", $file) . '-js';
				wp_enqueue_script('poka_load_' . $name, $url, array('jquery'), '', true);
			}
		}
		
		//Load lib assets
		public static function load_lib_assets($folder = '', $file = '', $type = ''){
			$url =  _POKA_PLUGIN_ASSET_URL_ . $folder . $file;
			
			if($type == 'css'){
				$name = str_replace(".min.css", "", $file) . '-css';
				wp_enqueue_style('poka_load_' . $name, $url);
			}
			
			if($type == 'js'){
				$name = str_replace(".min.js", "", $file) . '-js';
				wp_enqueue_script('poka_load_' . $name, $url, array('jquery'), '', true);
			}
		}
		
		//Get Msg BackEnd
		public static function getMsgBE($arr = array(), $type = 'success'){
			$html = "";
			if(count($arr) > 0){
				$classMsg = 'updated-message notice-success';
				
				if($type == 'error'){
					$classMsg = 'notice-error';
				}
				
				if($type == 'warning'){
					$classMsg = 'notice-warning';
				}
				
				$html = '<div class="update update-message notice is-dismissible inline ' . $classMsg . '">';
				foreach($arr as $key => $value){
					if(!empty($value)){
						$html .= '<p><strong> ' . $value . '</strong></p>';
					}
				}
				$html .= '</div>';
			}
			return $html;
		}
		
		/*
		    wp_enqueue_media(); //Post, Page, Category khong can keo
			wp_enqueue_script( 'multiple-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/html_multiple_box.js" , '', '', true );
			wp_enqueue_style( 'multiple-upload-media-style', _POKA_PLUGIN_URL_ . "css/upload-media/html_multiple_box.css");
		 */
		public static function getHtmlMultiBox($name, $value = array()){
			$image_size = 'thumbnail';
			
			if(is_array($value) && count($value) > 0){
				$list_attachment = "";
				foreach($value as $key => $val){
					if($image_attributes = wp_get_attachment_image_src($val["id"], $image_size)){
						$list_attachment .= '<li class="group-item ui-sortable-handle" data-attachment_id="' . $val["id"] . '">
									          	<div class="item"><img src="' . $image_attributes[0] . '" /></div>
									            <input type="hidden" name="' . $name . '[' . $key . '][id]" value="' . $val["id"] . '">
									            <div class="item"><input type="text" name="' . $name . '[' . $key . '][url]" class="link_redirect" placeholder="Redirect ..." value="' . $val["url"] . '"></div>
									            <span class="poka-btn-remove-image">x</span>
									         </li>';
					}
				}
				
				return '
			      <div class="poka_multi_box">
			         <div class="poka_images_container">
		                <ul class="product_images sortable " id="sortable">' . $list_attachment . '</ul>
			         </div>
			         <a href="#" data-gallery-name="' . $name . '" class="poka_upload_image_button">Thêm ảnh</a>
			      </div>';
			}else{
				return '<div class="poka_multi_box">
			      	<div class="poka_images_container"><ul class="product_images sortable " id="sortable"></ul></div>
			         <a href="#" data-gallery-name="' . $name . '" class="poka_upload_image_button">Thêm ảnh</a>
			      </div>';
			}
		}
		
		/*
		 wp_enqueue_media(); //Post, Page, Category khong can keo
		 wp_enqueue_style( 'multiple-upload-media-style', _POKA_PLUGIN_URL_ . "css/upload-media/multiple_upload_media.css");
		 wp_enqueue_script( 'multiple-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/multiple_upload_media.js" , '', '', true );
		*/
		public static function getHtmlImageMultiple($name, $value = array()){
			$image_size = 'thumbnail';
			
			if(is_array($value) && count($value) > 0){
				$list_attachment = "";
				foreach($value as $key => $val){
					$vID = $val;
					if($image_attributes = wp_get_attachment_image_src($vID, $image_size)){
						$list_attachment .= '<li class="item ui-sortable-handle" data-attachment_id="' . $vID . '">
									          <img src="' . $image_attributes[0] . '" />
									          <input type="hidden" name="' . $name . '[' . $key . ']" value="' . $vID . '">
									          <span class="poka-btn-remove-image">x</span>
									         </li>';
					}
				}
				
				return '
			      <div class="poka_image_gallery">
			         <div class="poka_images_container">
			                <ul class="product_images sortable " id="sortable">' . $list_attachment . '</ul>
			         </div>
			         <a href="#" data-gallery-name="' . $name . '" class="poka_upload_image_button">Thêm ảnh</a>
			      </div>';
			}else{
				return '
			      <div class="poka_image_gallery">
			      	<div class="poka_images_container"><ul class="product_images sortable " id="sortable"></ul></div>
			         <a href="#" data-gallery-name="' . $name . '" class="poka_upload_image_button">Thêm ảnh</a>
			      </div>';
			}
		}
		
		/*
		    wp_enqueue_media(); //Post, Page, Category khong can keo
		    wp_enqueue_script( 'single-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/single_upload_media.js" , '', '', true );
		 */
		public static function getHtmlImageSingle( $name, $value = '') {
			$image      = ' button">Choose';
			$image_size = 'thumbnail';
			$display = 'none';
			
			if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
				$image = '"><img src="' . $image_attributes[0] . '" style="display:block;" />';
				$display = 'inline-block';
				
			}
			
			return '
			      <div style="display: inline-block">
			          <a href="#" data-type-file="image" class="misha_upload_image_button' . $image . '</a>
			          <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			          <a href="#" class="misha_remove_image_button button" style="display:inline-block;display:' . $display . '">Remove</a>
			      </div>';
		}
		
		/*
		    wp_enqueue_media(); //Post, Page, Category khong can keo
			wp_enqueue_script( 'single-upload-media-script', _POKA_PLUGIN_URL_ . "js/upload-media/upload-file.js" , '', '', true );
		*/
		public static function getHtmlFile( $name, $value = '') {
			$display = 'none'; // display state ot the "Remove image" button
			
			if( $file_attributes = wp_get_attachment_url( $value ) ) {
				// $image_attributes[0] - image URL
				$file =  $file_attributes;
				$display = 'inline-block';
				
			}
			return '
			      <div>
			          <input type="text" data-type-file="application/msword" class="misha_upload_image_button" placeholder="Click to upload file" style="width: 78.5%;" value="'. $file .'"/>
			          <input type="hidden"name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			          <span href="#" class="misha_remove_file_button button button-primary" style="display:inline-block;display:' . $display . '">Xóa</span>
			      </div>';
		}
	
		//Lay duong dan hien tai URL
		public static function getSelfURL(){
			$pageURL = 'http';
			if($_SERVER["HTTPS"] == "on"){
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if($_SERVER["SERVER_PORT"] != "80"){
				$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
			}else{
				$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
			}
			
			return $pageURL;
		}
		
		public static function remove_all_size_images($id){
			$arrFileExt = array(
				'gif', 'jpeg', 'png', 'jpg'
			);
			
			$ext = pathinfo(wp_get_attachment_url($id), PATHINFO_EXTENSION);
			
			if(in_array($ext, $arrFileExt)){
				foreach(get_intermediate_image_sizes() as $key => $value){
					$image = wp_get_attachment_image_src($id, $value);
					
					if(!empty($image)){
						$pathRooot = substr(self::poka_get_home_path(), 0,strlen(self::poka_get_home_path()) - 1);
						$imagepath = str_replace(get_site_url(), $pathRooot, $image[0]);
						
						if(file_exists($imagepath)) {
							@unlink($imagepath);
						}
					}
				}
			}
			wp_delete_attachment($id, true);
		}
		/*===============================End Upload File*/
		
		//Check Url
		public static function is_valid_url($url){
			return preg_match('!^(http|https)://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?$!', $url);
		}
		
		//Check Email
		public static function is_valid_email($email){
			return preg_match('/^(([a-zA-Z0-9_.\-+!#$&\'*+=?^`{|}~])+\@((([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+|localhost) *,? *)+$/', $email);
		}
		
		//Check Number
		public static function is_numeric($value, $number_format = ""){
			switch($number_format){
				case "decimal_dot" :
					return preg_match("/^(-?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]+)?)$/", $value);
					break;
				case "decimal_comma" :
					return preg_match("/^(-?[0-9]{1,3}(?:\.?[0-9]{3})*(?:,[0-9]+)?)$/", $value);
					break;
				default :
					return preg_match("/^(-?[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})?)$/", $value) || preg_match("/^(-?[0-9]{1,3}(?:\.?[0-9]{3})*(?:,[0-9]{2})?)$/", $value);
				
			}
		}
		
		//Xoa tat ca khoang trang
		public static function trim_all($text){
			$text = trim($text);
			do{
				$prev_text = $text;
				$text      = str_replace("  ", " ", $text);
			}while($text != $prev_text);
			
			return $text;
		}
		
		//Xoa bo duong dan va cac thu muc, file ben trong
		public static function delete_directory($dirname){
			if(is_dir($dirname)){
				$dir_handle = opendir($dirname);
			}
			if(!$dir_handle){
				return false;
			}
			while($file = readdir($dir_handle)){
				if($file != "." && $file != ".."){
					if(!is_dir($dirname . "/" . $file)){
						unlink($dirname . "/" . $file);
					}else{
						self::delete_directory($dirname . '/' . $file);
					}
				}
			}
			closedir($dir_handle);
			rmdir($dirname);
			
			return true;
		}
		
		public static function getMimeType($file){
			// MIME types array
			$mimeTypes = array(
				"323"     => "text/h323",
				"acx"     => "application/internet-property-stream",
				"ai"      => "application/postscript",
				"aif"     => "audio/x-aiff",
				"aifc"    => "audio/x-aiff",
				"aiff"    => "audio/x-aiff",
				"asf"     => "video/x-ms-asf",
				"asr"     => "video/x-ms-asf",
				"asx"     => "video/x-ms-asf",
				"au"      => "audio/basic",
				"avi"     => "video/x-msvideo",
				"axs"     => "application/olescript",
				"bas"     => "text/plain",
				"bcpio"   => "application/x-bcpio",
				"bin"     => "application/octet-stream",
				"bmp"     => "image/bmp",
				"c"       => "text/plain",
				"cat"     => "application/vnd.ms-pkiseccat",
				"cdf"     => "application/x-cdf",
				"cer"     => "application/x-x509-ca-cert",
				"class"   => "application/octet-stream",
				"clp"     => "application/x-msclip",
				"cmx"     => "image/x-cmx",
				"cod"     => "image/cis-cod",
				"cpio"    => "application/x-cpio",
				"crd"     => "application/x-mscardfile",
				"crl"     => "application/pkix-crl",
				"crt"     => "application/x-x509-ca-cert",
				"csh"     => "application/x-csh",
				"css"     => "text/css",
				"dcr"     => "application/x-director",
				"der"     => "application/x-x509-ca-cert",
				"dir"     => "application/x-director",
				"dll"     => "application/x-msdownload",
				"dms"     => "application/octet-stream",
				"doc"     => "application/msword",
				"dot"     => "application/msword",
				"dvi"     => "application/x-dvi",
				"dxr"     => "application/x-director",
				"eps"     => "application/postscript",
				"etx"     => "text/x-setext",
				"evy"     => "application/envoy",
				"exe"     => "application/octet-stream",
				"fif"     => "application/fractals",
				"flr"     => "x-world/x-vrml",
				"gif"     => "image/gif",
				"gtar"    => "application/x-gtar",
				"gz"      => "application/x-gzip",
				"h"       => "text/plain",
				"hdf"     => "application/x-hdf",
				"hlp"     => "application/winhlp",
				"hqx"     => "application/mac-binhex40",
				"hta"     => "application/hta",
				"htc"     => "text/x-component",
				"htm"     => "text/html",
				"html"    => "text/html",
				"htt"     => "text/webviewhtml",
				"ico"     => "image/x-icon",
				"ief"     => "image/ief",
				"iii"     => "application/x-iphone",
				"ins"     => "application/x-internet-signup",
				"isp"     => "application/x-internet-signup",
				"jfif"    => "image/pipeg",
				"jpe"     => "image/jpeg",
				"jpeg"    => "image/jpeg",
				"jpg"     => "image/jpeg",
				"js"      => "application/x-javascript",
				"latex"   => "application/x-latex",
				"lha"     => "application/octet-stream",
				"lsf"     => "video/x-la-asf",
				"lsx"     => "video/x-la-asf",
				"lzh"     => "application/octet-stream",
				"m13"     => "application/x-msmediaview",
				"m14"     => "application/x-msmediaview",
				"m3u"     => "audio/x-mpegurl",
				"man"     => "application/x-troff-man",
				"mdb"     => "application/x-msaccess",
				"me"      => "application/x-troff-me",
				"mht"     => "message/rfc822",
				"mhtml"   => "message/rfc822",
				"mid"     => "audio/mid",
				"mny"     => "application/x-msmoney",
				"mov"     => "video/quicktime",
				"movie"   => "video/x-sgi-movie",
				"mp2"     => "video/mpeg",
				"mp3"     => "audio/mpeg",
				"mpa"     => "video/mpeg",
				"mpe"     => "video/mpeg",
				"mpeg"    => "video/mpeg",
				"mpg"     => "video/mpeg",
				"mpp"     => "application/vnd.ms-project",
				"mpv2"    => "video/mpeg",
				"ms"      => "application/x-troff-ms",
				"mvb"     => "application/x-msmediaview",
				"nws"     => "message/rfc822",
				"oda"     => "application/oda",
				"p10"     => "application/pkcs10",
				"p12"     => "application/x-pkcs12",
				"p7b"     => "application/x-pkcs7-certificates",
				"p7c"     => "application/x-pkcs7-mime",
				"p7m"     => "application/x-pkcs7-mime",
				"p7r"     => "application/x-pkcs7-certreqresp",
				"p7s"     => "application/x-pkcs7-signature",
				"pbm"     => "image/x-portable-bitmap",
				"pdf"     => "application/pdf",
				"pfx"     => "application/x-pkcs12",
				"pgm"     => "image/x-portable-graymap",
				"pko"     => "application/ynd.ms-pkipko",
				"pma"     => "application/x-perfmon",
				"pmc"     => "application/x-perfmon",
				"pml"     => "application/x-perfmon",
				"pmr"     => "application/x-perfmon",
				"pmw"     => "application/x-perfmon",
				"pnm"     => "image/x-portable-anymap",
				"pot"     => "application/vnd.ms-powerpoint",
				"ppm"     => "image/x-portable-pixmap",
				"pps"     => "application/vnd.ms-powerpoint",
				"ppt"     => "application/vnd.ms-powerpoint",
				"prf"     => "application/pics-rules",
				"ps"      => "application/postscript",
				"pub"     => "application/x-mspublisher",
				"qt"      => "video/quicktime",
				"ra"      => "audio/x-pn-realaudio",
				"ram"     => "audio/x-pn-realaudio",
				"ras"     => "image/x-cmu-raster",
				"rgb"     => "image/x-rgb",
				"rmi"     => "audio/mid",
				"roff"    => "application/x-troff",
				"rtf"     => "application/rtf",
				"rtx"     => "text/richtext",
				"scd"     => "application/x-msschedule",
				"sct"     => "text/scriptlet",
				"setpay"  => "application/set-payment-initiation",
				"setreg"  => "application/set-registration-initiation",
				"sh"      => "application/x-sh",
				"shar"    => "application/x-shar",
				"sit"     => "application/x-stuffit",
				"snd"     => "audio/basic",
				"spc"     => "application/x-pkcs7-certificates",
				"spl"     => "application/futuresplash",
				"src"     => "application/x-wais-source",
				"sst"     => "application/vnd.ms-pkicertstore",
				"stl"     => "application/vnd.ms-pkistl",
				"stm"     => "text/html",
				"svg"     => "image/svg+xml",
				"sv4cpio" => "application/x-sv4cpio",
				"sv4crc"  => "application/x-sv4crc",
				"t"       => "application/x-troff",
				"tar"     => "application/x-tar",
				"tcl"     => "application/x-tcl",
				"tex"     => "application/x-tex",
				"texi"    => "application/x-texinfo",
				"texinfo" => "application/x-texinfo",
				"tgz"     => "application/x-compressed",
				"tif"     => "image/tiff",
				"tiff"    => "image/tiff",
				"tr"      => "application/x-troff",
				"trm"     => "application/x-msterminal",
				"tsv"     => "text/tab-separated-values",
				"txt"     => "text/plain",
				"uls"     => "text/iuls",
				"ustar"   => "application/x-ustar",
				"vcf"     => "text/x-vcard",
				"vrml"    => "x-world/x-vrml",
				"wav"     => "audio/x-wav",
				"wcm"     => "application/vnd.ms-works",
				"wdb"     => "application/vnd.ms-works",
				"wks"     => "application/vnd.ms-works",
				"wmf"     => "application/x-msmetafile",
				"wps"     => "application/vnd.ms-works",
				"wri"     => "application/x-mswrite",
				"wrl"     => "x-world/x-vrml",
				"wrz"     => "x-world/x-vrml",
				"xaf"     => "x-world/x-vrml",
				"xbm"     => "image/x-xbitmap",
				"xla"     => "application/vnd.ms-excel",
				"xlc"     => "application/vnd.ms-excel",
				"xlm"     => "application/vnd.ms-excel",
				"xls"     => "application/vnd.ms-excel",
				"xlsx"    => "vnd.ms-excel",
				"xlt"     => "application/vnd.ms-excel",
				"xlw"     => "application/vnd.ms-excel",
				"xof"     => "x-world/x-vrml",
				"xpm"     => "image/x-xpixmap",
				"xwd"     => "image/x-xwindowdump",
				"z"       => "application/x-compress",
				"zip"     => "application/zip"
			);
			
			$extension = end(explode('.', $file));
			
			return $mimeTypes[ $extension ]; // return the array value
		}
		
		//RandomString
		function generateRandomString($length = 10){
			$characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString     = '';
			for($i = 0; $i < $length; $i ++){
				$randomString .= $characters[ rand(0, $charactersLength - 1) ];
			}
			
			return $randomString;
		}
		
		public static function convertCapitalizeString($sString = "", $sDelimiter = " ", $nSkip = - 1){
			if(!empty($sDelimiter) && !empty($sString)){
				$aString = explode($sDelimiter, $sString);
				
				if(count($aString)){
					for($i = 0; $i < count($aString); $i ++){
						if($nSkip >= 0 && $nSkip = $i){
							continue;
						}
						
						$aString[ $i ] = ucfirst($aString[ $i ]);
					}
				}
				
				return implode("", $aString);
			}
		}
	}