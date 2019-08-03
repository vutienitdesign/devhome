<?php
	global $pokaHelper;
	$pokaHelper = new POKA_Helper();
	class POKA_Helper{
		public function xuLyCategory($arrCat = array()){
			$result = array();
			
			//Them category
			$catName = $arrCat['category'];
			if(!empty($catName)){
				$oTerm = term_exists($catName, 'product_cat', 0);
				if(!$oTerm){
					$oTerm = wp_insert_term($catName, 'product_cat', array('parent' => 0));
				}
				$result[] = $catName;
				
				
				//Them category child
				if(!empty($arrCat['subcategory'])){
					foreach($arrCat['subcategory'] as $v){
						$catNameChild = trim($v);
						
						$oTermChild = term_exists($catNameChild, 'product_cat', array('parent' => $oTerm['term_id']));
						if(!$oTermChild){
							$oTermChild = wp_insert_term($catNameChild, 'product_cat', array('parent' => $oTerm['term_id']));
						}
						
						$result[] = $catNameChild;
					}
				}
			}
			
			return $result;
		}
		
		public function xuLyImageGoogleCound($url, $userID){
			$aImage = explode('\\\\192.168.9.3\Live Order Data\\', $url);
			$aImage = explode('\\', $aImage[1]);
			$sUrl = '';
			foreach($aImage as $v){
				$sUrl .= '/' . $v;
			}
			
			$fileFullName = basename($sUrl);
			$aFileName    = explode('.', $fileFullName);
			$fileName     = $aFileName[0];
			$fileNameExt  = $aFileName[1];
			
			$arrExt = array(
				'jpg' => 'jpeg',
				'png' => 'png',
				'gif' => 'gif',
			);
			
			$miniType = 'image/' . (!empty($arrExt[$fileNameExt]) ? $arrExt[$fileNameExt] : 'png');
			$arr = array(
				'post_title'     => $fileName,
				'post_author'    => $userID,
				'post_status'    => 'inherit',
				'comment_status' => 'open',
				'ping_status'    => 'closed',
				'post_type'      => 'attachment',
				'post_mime_type' => $miniType,
			);
			$post_id = wp_insert_post($arr);
			if($post_id){
				$aArr = array(
					'provider' => 'gcp',
					'region'   => 'asia-east2',
					'bucket'   => 'mrus',
					'key'      => $sUrl
				);
				update_post_meta( $post_id, 'amazonS3_info', $aArr);
				
				$aArr = array(
					'width'  => 482,
					'height' => 303,
					'file'   => $sUrl,
					'sizes' => array(
						'thumbnail' => array(
							'file'      => $fileFullName,
							'width'     => '150',
							'height'    => '150',
							'mime-type' => $miniType,
						),
						'medium' => array(
							'file'      => $fileFullName,
							'width'     => '300',
							'height'    => '189',
							'mime-type' => $miniType,
						),
					),
					'image_meta' => array(
						'aperture'          => 0,
						'credit'            => '',
						'camera'            => '',
						'caption'           => '',
						'created_timestamp' => 0,
						'copyright'         => '',
						'focal_length'      => 0,
						'iso'               => 0,
						'shutter_speed'     => 0,
						'title'             => '',
						'orientation'       => 0,
						'keywords'          => array()
					)
				);
				update_post_meta( $post_id, '_wp_attachment_metadata', $aArr);
				update_post_meta( $post_id, '_wp_attached_file', $sUrl);
			}
			
			return $post_id;
		}
		
		public function xuLyImages($obj){
			$aImg = array();
			if(!empty($obj)){
				$sImage = ltrim($obj, '[ {');
				$sImage = rtrim($sImage, '} ]');
				$aImage = explode('}, {', $sImage);
				
				if(!empty($aImage)){
					foreach($aImage as $kImg => $vImg){
						$vImg = '{' . $vImg . '}';
						$aImg[$kImg] = json_decode($vImg);
					}
				}
			}
			return $aImg;
		}
	}