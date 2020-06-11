<?php
class util
{

	/*
                      Class Name util (PHP 4 >= 4.3.0, PHP 5)
 -------------------------------------------------------------------------------------------------------------
 *  Source Path		: /include/util.php (PHP 4 >= 4.3.0, PHP 5)
 *  시스템명		: 
 * 업무대중소분류	:
 * 프로그램설명		: utility 라이브러리
 * 파일명			: util.php
 * Called By		: All
 * Calling			:
 *  작성자			: 황명제
 *  작성날짜		: 2008. 11. 01
 * ----------------------------------------------------------------------
 * 변경사항
 * 변경일자	    변경자		    내용
 * -------		-----			------------------------------
 * 2008.11.01	황명제			새로 만듬

--------사용법 샘플-----------------------------------------------------------
	include "../include/util.php";
	util::page_list(1,$tb_nm,$where,10,$url);
	

--------function list-----------------------------------------------------------
function sendRedirect($msg,$url)
function sendTopRedirect($msg,$url)
function msg($msg)
function errormsg($msg)
function page_list($current_Page="1", $table_name="", $whereStr="", $list_num="12",$url="") {
function Page()
function getTel($tel) {
function fileUpload($value,$dir,$file_size,$extension_check)
function fileUpload2($value,$dir,$file_size,$extension_check)
function dir_check($save_dir)
function perms_check($fileStr) {
function filesize($file)
function is_image($file)
function image_type($file)
function resize_image($file, $save_dir, $w, $h, $name)
function sub_string2($msg,$cut_size,$last)
function sub_string($msg,$cut_size,$last)
function cut_str($str,$maxlen, $suffix = "..") {
function is_null($str)
function html_entity_encode($str)
function html_entity_decode($str)
function path($value,$path)
function remove_param($url, $value)
function str_pos($str,$search)
function today($sep=".")
function ntime($sep=":")
function format_phone($str)
 -------------------------------------------------------------------------------------------------------------

*/
	// 특정 URL로 이동
	function sendRedirect($msg,$url)
	{
		if (strlen($msg) == 0) {
			echo ("<script language='javascript'>location.href='$url'</script>");
		} else {
			echo ("<script language='javascript'>alert(\"$msg\")</script>");
			echo ("<script language='javascript'>location.href='$url'</script>");
		}
	}	// end sendRedirect

	// 특정 URL로 top page 이동
	function sendTopRedirect($msg,$url)
	{
		if (strlen($msg) == 0) {
			echo ("<script language='javascript'>top.location.href='$url'</script>");
		} else {
			echo ("<script language='javascript'>alert(\"$msg\")</script>");
			echo ("<script language='javascript'>top.location.href='$url'</script>");
		}
	}	// end sendRedirect

	// ALERT를 이용한 메시지 출력
	function msg($msg)
	{
		echo ("<script language='javascript'>alert(\"$msg\")</script>");
	}


	// 에러 메시지 출력
	function errormsg($msg)
	{
		echo ("<script language=\"javascript\">
			alert(\"$msg\");
			history.go(-1);
		</script>");

	}

	function page_list($current_Page="1", $table_name="", $whereStr="", $list_num="12",$url="") {
		$sql = "select count(*) as cnt from $table_name $whereStr";
		$result = mysql_query($sql);
		$rows = mysql_fetch_array($result);
		$total_Rec = $rows[cnt];
		$total_Page = (int)($total_Rec / $list_num) ;
		if (($total_Rec % $list_num)>0) $total_Page ++;

		$start_Page=(int)(($current_Page-1)/10)*10+1;
		$end_Page=$start_Page+9;
		if ($end_Page>$total_Page) $end_Page=$total_Page;

		$lisk_str="";
		$i=$start_Page-1;
		if ($start_Page>10) $link_str .="<a href='$url"."&page=1'><img src='/btn/btn_loc1.gif' border=0></a> <a href='$url&page=$i'><img src='/btn/btn_loc2.gif' border=0></a>";
		for ($i=$start_Page;$i<=$end_Page;$i++) {
				if ($current_Page != $i) {
					$link_str .= "&nbsp;<a href='$url&page=$i'>[$i]</a>";
				} else {
					$link_str .= "&nbsp;<font color=red size=2><b>$i</b></font>";
				}
			}

		if ($total_Page>$end_Page) $link_str .="&nbsp;<a href='$url&page=$i'><img src='/btn/btn_loc3.gif' border=0></a>&nbsp;<a href='$url&page=$total_Page'><img src='/btn/btn_loc4.gif' border=0></a>";
		$link_str .="&nbsp;<font size=2>총 레코드=$total_Rec / 총 페이지=$total_Page</font>";
		return $link_str;


	}

	//페이지 이동
	function Page()
	{
		global $page,$total_page,$page_per_block,$first_page,$last_page,$total_record,$last;
		$bbsdir=""; //"/php"; // 2008/09/20

		##### 이전페이지가 존재할 경우 이전페이지로 가는 링크를 활성화시킨다.
		if ($page > 1) {
			$page_num = $page - 1;
			echo ("<a href=\"javascript:Page('$page_num')\"><img src=\"$bbsdir/btn/prevo.gif\" align=absmiddle></a>&nbsp;");
		} else {
			echo ("<img src=\"$bbsdir/btn/prev.gif\" align=absmiddle>");
		}

		echo "&nbsp;";

		##### 게시물 목록 하단의 각 페이지로 직접 이동할 수 있는 페이지 링크에 대한 설정을 한다.
		$total_block = ceil($total_page/$page_per_block);
		$block = ceil($page/$page_per_block);

		$first_page = ($block-1)*$page_per_block;
		$last_page = $block*$page_per_block;

		if($total_block <= $block) {
			$last_page = $total_page;
		}

		##### 이전페이지블록에 대한 페이지 링크
		if($block > 1) {
			$my_page = $first_page;
			echo("<a href=\"javascript:Page('$my_page')\">[이전 ${page_per_block}개]</a>&nbsp;&nbsp;&nbsp;");
			//echo("<a href=\"javascript:Page('$my_page')\"><img src='admin/img/board_prev02.gif' border='0'>");
		}

		##### 현재의 페이지 블록범위내에서 각 페이지로 바로 이동할 수 있는 하이퍼링크를 출력한다.
		for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
			echo "&nbsp;";
			if($page == $direct_page) {
				echo("<font color=#FF6600><b>$direct_page</b></font>");
			} else {
				echo("<a href=\"javascript:Page('$direct_page')\">$direct_page</a>");
			}
			echo "&nbsp;";
		}

		##### 다음페이지블록에 대한 페이지 링크
		if($block < $total_block) {
			$my_page = $last_page+1;
			echo("&nbsp;&nbsp;&nbsp;<a href=\"javascript:Page('$my_page')\">[다음 ${page_per_block}개]</a>");
			//echo("<a href=\"javascript:Page('$my_page')\"><img src='/admin/img/board_next02.gif' border='0'></a>");
		}

		echo "&nbsp;";

		##### 게시물이 다음페이지에도 존재할 경우 다음페이지로 가는 링크를 활성화시킨다.
		if ($total_record > $last) {
			$page_num = $page + 1;
			echo ("<a href=\"javascript:Page('$page_num')\"><img src=\"$bbsdir/btn/nexto.gif\" align=absmiddle></a>&nbsp;");
		} else {
			echo ("<img src=\"$bbsdir/btn/next.gif\" align=absmiddle>");
		}
	}//end Page


	// 전화번호를 원상태로 복구 (저장된 전화번호 값에 있는 'X' 문자를 제거한다.)
	function getTel($tel) {
		$telStr[0] = str_replace("X","",substr($tel,0,4));
		$telStr[1] = str_replace("X","",substr($tel,4,4));
		$telStr[2] = str_replace("X","",substr($tel,8,4));

		return $telStr;
	}


/*
###########################################################################################################
##########
##########	파일 관련 함수
##########
###########################################################################################################
*/
	/*
	* ### 업로드 파일 ###
	* POST 방식으로 업로드된 파일을 서버에 전송한다.
	* @param value				POST방식으로 전송된 파일명
	* @param dir				파일을 복사할 DIR
	* @param file_size			업로드 가능한 최대 용량
	* @param extension_check	업로드가 불가능한 파일 확장자(배열)
	* @return
	* ### Example ###
	* EX   : fileUpload("image.jpg","/image","5",array);
	* 결과 : true
	*/
	function fileUpload($value,$dir,$file_size,$extension_check)
	{
		//파일이 저장될 DIR
		$save_dir = $_SERVER["DOCUMENT_ROOT"] . $dir . DIRECTORY_SEPARATOR;

		//정상적인 파일인지 검색 한다.
		if (is_uploaded_file($_FILES[$value]['tmp_name']) && $_FILES[$value]['size'] > 0 ) {
			//dir이 존재 하는지 확인하다.
			if (!is_dir($save_dir)) {
				util::msg("존재하지 않는 DIR 입니다.");
				return;
			}

			//확장자를 제외한 파일 이름(임시)
			$file_name_tmp = ereg_replace("(\.[^\.]*$)","",$_FILES[$value]['name']);

			//파일이름을 제외한 확장자(임시)
			$extension_tmp = str_replace($file_name_tmp, "" , $_FILES[$value]['name']);


			//업로드 할수 없는 파일을 체크 한다.
			if ($extension_check != "" || is_array($extension_check)) {
				//업로드 할수 없는 파일 체크
				$extension_check_tmp = strtolower(str_replace(".","",$extension_tmp));

				if (is_array($extension_check)) {
					if (in_array($extension_check_tmp, $extension_check)) {
						util::msg("업로드 할수 없는 파일 확장자 입니다!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				} else {
					if (strcasecmp($extension_check_tmp,$extension_check) == 0) {
						util::msg("업로드 할수 없는 파일 확장자 입니다!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				}
			}

			//파일의 용량 체크
			if ( ($_FILES[$value]['size']) > ($file_size * 1024 * 1024) ) {
				util::msg("업로드 용량이 초과 되었습니다\\n업로드 최대 용량은 $file_size MB 입니다.");
				unlink($_FILES[$value]['tmp_name']);
				return;
			}


			//업로드될 파일 경로
			$check_file = $save_dir . strtolower($_FILES[$value]['name']);

			//업로드될 파일중에 같은 이름의 파일이 있는지 체크
			$i = 1;
			while (file_exists($check_file) == true) {
				$check_file = $save_dir . strtolower($file_name_tmp . "_" . $i . $extension_tmp);
				$i++;
			}

			//파일을 복사한다.
			if (!copy($_FILES[$value]['tmp_name'], $check_file)) {
				util::msg("파일을 업로드하는데 실패 했습니다!");
				return;
			}

			//임시Dir에 저장했던 파일을 지운다.
			if (!@unlink($_FILES[$value]['tmp_name'])) {
				util::msg("임시파일을 삭제 하는데 실패 하였습니다!");
			}


			//경로명에서 파일이름만 반환합니다.
			$fileName = basename($check_file);
		}

		return $fileName;
	}//end fileUpload


	function fileUpload2($value,$dir,$file_size,$extension_check)
	{
		//파일이 저장될 IDR
		$save_dir = $_SERVER["DOCUMENT_ROOT"] . $dir . DIRECTORY_SEPARATOR;

		//정상적인 파일인지 검색 한다.
		if (is_uploaded_file($_FILES[$value]['tmp_name']) && $_FILES[$value]['size'] > 0 ) {

			//dir이 존재 하는지 확인하다.
			if (!is_dir($save_dir)) {
				util::msg("존재하지 않는 DIR 입니다.");
				return;
			}

			//확장자를 제외한 파일 이름(임시)
			$file_name_tmp = ereg_replace("(\.[^\.]*$)","",$_FILES[$value]['name']);

			//파일이름을 제외한 확장자(임시)
			$extension_tmp = str_replace($file_name_tmp, "" , $_FILES[$value]['name']);


			//업로드 할수 없는 파일을 체크 한다.
			if ($extension_check != "" || is_array($extension_check)) {

				//업로드 할수 없는 파일 체크
				$extension_check_tmp = strtolower(str_replace(".","",$extension_tmp));

				if (is_array($extension_check)) {
					if (in_array($extension_check_tmp, $extension_check)) {
						util::msg("업로드 할수 없는 파일 확장자 입니다!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				} else {
					if (strcasecmp($extension_check_tmp,$extension_check) == 0) {
						util::msg("업로드 할수 없는 파일 확장자 입니다!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				}
			}

			//파일의 용량 체크
			if ( ($_FILES[$value]['size']) > ($file_size * 1024 * 1024) ) {
				util::msg("업로드 용량이 초과 되었습니다\\n업로드 최대 용량은 $file_size MB 입니다.");
				unlink($_FILES[$value]['tmp_name']);
				return;
			}


			//업로드될 파일 경로
			//$check_file = $save_dir . strtolower($_FILES[$value]['name']);
			$check_file = $save_dir . time() . strtolower($extension_tmp);

			//업로드될 파일중에 같은 이름의 파일이 있는지 체크
			/*
			$i = 1;
			while (file_exists($check_file) == true) {
				$check_file = $save_dir . strtolower( time() . "_" . $i . $extension_tmp) ;
				$i++;
			}
			*/

			//파일을 복사한다.
			if (!copy($_FILES[$value]['tmp_name'], $check_file)) {
				util::msg("파일을 업로드하는데 실패 했습니다!");
				return;
			}

			//임시Dir에 저장했던 파일을 지운다.
			if (!@unlink($_FILES[$value]['tmp_name'])) {
				util::msg("임시파일을 삭제 하는데 실패 하였습니다!");
			}


			//경로명에서 파일이름만 반환합니다.
			$fileName = basename($check_file);
		}

		return $fileName;
	}//end fileUpload2


	/*
	* ### Dir 생성 함수 ###
	* @param save_dir    생성활 Dir 명[array]
	* @return
	* ### Example ###
	* EX   : dir_check(array("/dir_name", "/dir_name/dir_name");
	* 결과 : true
	*/
	function dir_check($save_dir)
	{

		//리턴할 메시지
		$new_array = array();

		for ($i=0;$i<sizeof($save_dir);$i++)
		{
			$get_dir[$i] = $save_dir[$i];
			$dir_check[$i] = $_SERVER["DOCUMENT_ROOT"] . $get_dir[$i];


			//dir 존재 유무 체크, dir이 없으면 dir 생성;
			if (!is_dir($dir_check[$i])) {
				if (!@mkdir($dir_check[$i],0777)) {
					util::errormsg("파일을 저장 할 Dir($dir_check[$i]) 를 생성 할수 없습니다!");
					exit();
				}
			}


			/*
			//퍼미션 변경
			if (!@chmod($dir_check[$i],0777)) {
				util::errormsg("$dir_check[$i] 의 퍼미션을 변경 할 수 없습니다!");
				exit();
			}

			//파일의 퍼미션 체크
			$file_perms[$i] = substr(sprintf("%o", fileperms($dir_check[$i])), -3);

			//퍼미션 체크
			if ($file_perms[$i] != 777) {
				util::errorMsg("$dir_check[$i] 퍼미션이 올바르지 않습니다!<br>퍼미션을 777로 변경하여 주세요!");
				exit();

			}
			*/

		}//end for

		return;
	}//end dir_check;


	function perms_check($fileStr) {
		//리턴할 메시지
		$new_array = array();

		//파일이 존재 하는지 검색
		if (!file_exists($fileStr)) {
			//$this->errorMsg("$fileStr 파일이 존재 하지 않습니다!",1);
			$r_msg = array_push($new_array,$fileStr . "파일이 존재 하지 않습니다!");
		}

		//퍼미션 변경
		if (!@chmod($fileStr,0777)) {
			//$this->errorMsg("$fileStr 의 퍼미션을 변경 할 수 없습니다!",1);
			$r_msg .= array_push($new_array,$fileStr . "의 퍼미션을 변경 할 수 없습니다!");
		}

		//파일의 퍼미션 체크
		$file_perms = substr(sprintf("%o", fileperms($fileStr)), -3);

		//퍼미션 체크
		if ($file_perms != 777) {
			//$this->errorMsg("$fileStr 퍼미션이 올바르지 않습니다!\\n퍼미션을 777로 변경하여 주세요!",1);
			$r_msg .= array_push($new_array,$fileStr . "퍼미션이 올바르지 않습니다!. 퍼미션을 777로 변경하여 주세요!");
		}

	}	// end perms_check


	/*
	* ### 파일의 사이즈를 확인 한다. ###
	* @param file	파일명
	* @return
	* ### Example ###
	* EX   : filesize("sample.html")
	* 결과 : array[0] = 파일이름, array[1] = 파일 사이즈, array[2] = 파일의 단위
	*/
	function filesize($file)
	{
		if (file_exists($file) == false) {
			return;
		}

		$i=0;
		$size = filesize($file);
		$iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
		while (($size/1024)>1) {
			$size=$size/1024;
			$i++;
		}

		$info[0] = basename($file);
		$info[1] = substr($size,0,strpos($size,'.')+4);
		$info[2] = $iec[$i];

		return $info;
	}


/*
###########################################################################################################
##########
##########	이미지 관련 함수
##########
###########################################################################################################
*/
	/*
	* ### 파일이 이미지 인지 확인한다.(파일이 이미지 이면 true, 아니면 false 반환한다) ###
	* @param file    파일명
	* @return
	* ### Example ###
	* EX   : is_image("image.jpg")
	* 결과 : true
	*/
	function is_image($file)
	{
		if (file_exists($file) == true && function_exists('getimagesize')) {
			//이미지 사이즈 및 정보를 배열로 리턴한다.
			$image_type = @GetImageSize($file);

			//이미지 정보를 반환한다.
			$img_type = $image_type[mime];

			switch($img_type) {
				case 'image/bmp' :					return true;
				case 'image/cis-cod' :				return true;
				case 'image/gif' :					return true;
				case 'image/ief' :					return true;
				case 'image/jpeg' :					return true;
				case 'image/pipeg' :				return true;
				case 'image/tiff' :					return true;
				case 'image/x-cmu-raster' :			return true;
				case 'image/x-cmx' :				return true;
				case 'image/x-icon' :				return true;
				case 'image/x-portable-anymap' :	return true;
				case 'image/x-portable-bitmap' :	return true;
				case 'image/x-portable-graymap':	return true;
				case 'image/x-portable-pixmap' :	return true;
				case 'image/x-rgb' :				return true;
				case 'image/x-xbitmap' :			return true;
				case 'image/x-xpixmap' :			return true;
				case 'image/x-xwindowdump' :		return true;
				case 'image/png' :					return true;
				case 'image/x-jps' :				return true;
				case 'image/x-freehand' :			return true;
				default : return false;
			}
		}

		return false;
	}	// end is_image


	/*
	* ### 이미지 type을 반환한다. ###
	* @param file	파일명
	* @return
	* ### Example ###
	* EX   : is_image_type("image.jpg")
	* 결과 : jpg
	*/
	function image_type($file)
	{
		if (file_exists($file) == true && function_exists('getimagesize')) {

			//이미지 사이즈 및 정보를 배열로 리턴한다.
			$image_type = @GetImageSize($file);

			//이미지 정보를 리턴한다.
			$img_type = $image_type[mime];

			switch($img_type) {
				case 'image/bmp' :					return 'bmp';
				case 'image/cis-cod' :				return 'cod';
				case 'image/gif' :					return 'gif';
				case 'image/ief' :					return 'ief';
				case 'image/jpeg' :					return 'jpg';
				case 'image/pipeg' :				return 'jfif';
				case 'image/tiff' :					return 'tif';
				case 'image/x-cmu-raster' :			return 'ras';
				case 'image/x-cmx' :				return 'cmx';
				case 'image/x-icon' :				return 'ico';
				case 'image/x-portable-anymap' :	return 'pnm';
				case 'image/x-portable-bitmap' :	return 'pbm';
				case 'image/x-portable-graymap':	return 'pgm';
				case 'image/x-portable-pixmap' :	return 'ppm';
				case 'image/x-rgb' :				return 'rgb';
				case 'image/x-xbitmap' :			return 'xbm';
				case 'image/x-xpixmap' :			return 'xpm';
				case 'image/x-xwindowdump' :		return 'xwd';
				case 'image/png' :					return 'png';
				case 'image/x-jps' :				return 'jps';
				case 'image/x-freehand' :			return 'fh';
				default : return false;
			}//end switch
		}//end if

		return false;
	}


	/*
	* ### 이미지 크기 변경하기 ###
	* @param file		크기를 변경할 이미지
	* @param save_dir	변경된 이미지가 저장될 DIR
	* @param w			변경될 이미지의 Width
	* @param h			변경될 이미지의 Height
	* @param name		변경될 이미지의 파일 이름
	* @return
	* ### Example ###
	* EX   : resize_image("imagetest.jpg,"/image",100,100,"resize")
	* 결과 : imagetest_resize.jpg
	*/
	function resize_image($file, $save_dir, $w, $h, $name)
	{
		//파일이 존재 하는지 확인한다.
		if (file_exists($file) == true) {
			if (util::image_type($file) == "jpg" || util::image_type($file) == "gif") {

				//이미지 사이즈 및 정보를 리턴한다.
				$img_size = @GetImageSize($file);

				//새로운 투루컬러 이미지를 생성한다.
				$dst_image = ImageCreateTrueColor($w,$h);

				//확장자를 제외한 파일 이름
				$file_name = ereg_replace("(\.[^\.]*$)","", basename($file));

				//파일이름을 제외한 확장자
				$extension = str_replace($file_name, "" , basename($file));

				//크기가 변경될 이미지 경로
				$save_file = $save_dir . "/" .  strtolower($file_name . "_" . $name . $extension);

				//같은 이름의 이미지가 있을경우 이미지 이름 변경
				$i = 1;
				while (file_exists($save_file) == true) {
					$save_file = $save_dir . "/" .  strtolower($file_name . "_" . $name . "_" . $i . $extension);
					$i++;
				}

				//이미지 타입이 jpg 이면...
				if (util::image_type($file) == "jpg") {

					//새로운 JPG 파일을 생성한다.
					$image = ImageCreateFromJpeg($file);

					//빈 이미지에다 원본이미지를 그린다.
					ImagecopyResampled($dst_image, $image, 0, 0, 0, 0, $w, $h, $img_size[0], $img_size[1]);

					//JPG이미지를 생성한다.
					ImageJpeg($dst_image, $save_file, 100);

					//메모리 반환
					ImageDestroy($dst_image);

					//경로명에서 파일이름만 반환합니다.
					return basename($save_file);

				} else if (util::image_type($file) == "gif") {

					//새로운 GIF 파일을 생선한다.
					$image = ImageCreateFromGif($file);

					//빈 이미지에다 원본이미지를 그린다.
					ImagecopyResampled($dst_image, $image, 0, 0, 0, 0, $w, $h, $img_size[0], $img_size[1]);	
					
					//GIF 이미지를 생성 한다.
					ImageGif($dst_image, $save_file, 100);

					//메모리 반환
					ImageDestroy($dst_image);

					//경로명에서 파일이름만 반환합니다.
					return basename($save_file);
				}

			}//end if

		}// end if ##file_exists($file) == true

	}	// end resize_image

	// 그림 보여줌
	function image_view( $filename, $filerename, $viewfile, $viewsize, $img_dir )
	{
		$file_type = strtolower( substr( strrchr( $filename, "." ), 1 ) ) ;
		if( $file_type == "jpg" || $file_type == "gif" || $file_type == "bmp" || $file_type == "png" ) {
			$image_view = stripslashes( $filerename ) ;
			$img_array  = getimagesize( $img_dir . "/" . $image_view ) ;

			if( $img_array[0] > 580 ) $img_width = "580" ;
			else $img_width = $img_array[0];

			if( $img_array[0] > 900 ) $img_width1 = "900";
			else $img_width1 = $img_array[0];
			if( $img_array[1] > 635 ) $img_height1 = "635";
			else $img_height1 = $img_array[1];
			$bbb = $img_width1 + 18;
			$ccc = $img_height1 + 18;

			$full_path = "$img_dir/$image_view" ;
			echo("
			<a href='#' onClick=\"javascript:window.open('$viewfile?path=$full_path','full_path','left=0, top=0, toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=$bbb, height=$ccc')\"><img src='$img_dir/$image_view' width='$viewsize' height='80' border='0'></a>
			");
		}
	}

/*
##########################################################################################################
##########
##########	문자열 관련 함수
##########
##########################################################################################################
*/
	//문자열 자르기
	function sub_string2($msg,$cut_size,$last)
	{
		if($cut_size > strlen($msg)) return $msg;

		$ishan1=0;
		$ishan2=0;
		for ($i=0;$i<strlen($msg);$i++) {
			if ($ishan1 == 1) $ishan2 = 1;

			if(ord($msg[$i]) > 127 && $ishan1 == 0)  {
				$ishan2=0;
				$ishan1=1;
			}

			if($ishan2 == 1) $ishan1=0;

			if (($i+1)==$cut_size) {
				if ($ishan2!=1) break;
				$temp_str.=$msg[$i];
				break;
			}

			$temp_str.=$msg[$i];
		}//end for

		$temp_str.= $last;
		return $temp_str;
	}	// end sub_string2


	/*
	* ### 문자열 자르기 ###
	* @param msg		문자
	* @param cut_size	자를 문자열 길이
	* @param last		문자열을 자른후 문자열 뒤에 붙을 문자
	* @return
	* ### Example ###
	* EX   : sub_mbstring("안녕하세요",3,"...")
	* 결과 : 안녕하...
	*/
	function sub_string($msg,$cut_size,$last)
	{
		if (function_exists('mb_substr')) {
			mb_internal_encoding("euc-kr");
			if ($cut_size > strlen($msg))  {
				return $msg;
			} else {
				return mb_substr($msg,0,$cut_size) . $last;
			}
		} else {
			return util::sub_string2($msg,$cut_size,$last);
		}
	}	// end sub_string

	################################################################################
	// 문자열 끊기 (이상의 길이일때는 ... 로 표시)
	################################################################################
	function cut_str($str,$maxlen, $suffix = "..") {
		if($maxlen<=0) return $str;
		if(ereg("\[re\]",$str)) $len=$len+4;
		if($maxlen >= strlen($str)) return $str;

		$klen = $maxlen - 1;
		while(ord($str[$klen]) & 0x80) $klen--;
		
		return substr($str, 0, $maxlen - (($maxlen + $klen + 1) % 2)).$suffix;
	}

	/*
	* ### 문자열이 null 인지 확인 한다###
	* @param msg		문자
	* @return
	* ### Example ###
	* EX   : is_null("안녕하세요")
	* 결과 : false
	*/
	function is_null($str)
	{
		if(eregi("[^[:space:]]+",$str)) {
			return false;
		} else {
			return true;
		}

		return true;
	}


	/*
	*/
	function html_entity_encode($str)
	{
		$info = str_replace("\"","&quot;",str_replace("'","&#039;",$str));
		return $info;
	}

	/*

	*/
	function html_entity_decode($str)
	{
		$info = str_replace("&quot;","\"",str_replace("&#039;","'",$str));
		return $info;
	}


	function path($value,$path)
	{
		$path_pos_s   = strpos($path, $value);
		$path_pos_cut = substr($path, $path_pos_s);
		$path_pos_e   = strpos($path_pos_cut,"&");

		if ($path_pos_e == false) {
			$PATH = substr($path,$path_pos_s);
		} else {
			$PATH = substr($path,$path_pos_s,$path_pos_e);
		}


		$remove_path = str_replace(trim($PATH),"",$path);

		if (substr_count($remove_path,"&&") == 0) {
			$r_param = substr($remove_path,-1);
			if ($r_param == "&") {
				$r_param = substr($remove_path,0,strlen($remove_path)-1);
			}
		} else {
			$r_param = str_replace("&&","&",$remove_path);
		}

		$r_path = str_replace($value,"",$PATH);

		return $r_path . "?" . $r_param;
	}


	/*
	 * url 로 부터 특정 parameter 에 해당하는 것을 삭제한 후에 url 을 반환한다.
	*/
	function remove_param($url, $value)
	{
		if ($value == "" || !$value) {
			return $url;
		}

		$param_pos_s   = strpos($url,$value);
		$param_pos_cut = substr($url,$param_pos_s);
		$param_pos_e   = strpos($param_pos_cut,"&");

		if ($param_pos_s == false) {
			return $url;
		}

		if ($param_pos_e == false) {
			$URL = substr($url,$param_pos_s);
		} else {
			$URL = substr($url, $param_pos_s, $param_pos_e);
		}

		$remove_path = str_replace(trim($URL),"",$url);

		if (substr_count($remove_path,"&&") == 0) {
			if (substr($remove_path,0,1) == "&") {
				$r_param = substr($remove_path,1);
			} else if (substr($remove_path,-1) == "&") {
				$r_param = substr($remove_path,0,strlen($remove_path)-1);
			}
		} else {
			$r_param = str_replace("&&","&",$remove_path);
		}

		return $r_param;
	}


	/*
	* ### 마지막에 나타난 문자열을 찾아 배열로 리턴한다. ###
	* @param str    문자열
	* @param search 찿는 문자
	* @return Array
	* ### Example ###
	* EX   : str_pos("abcde.abc", ".");
	* 결과 : array[0] = abcde, array[1] = abc
	*/
	function str_pos($str,$search)
	{
		$pos = strrpos($str, $search);
		$return_str = array();
		if ($pos === false) { // 주의: 등호 3개
			$return_str[0] = $str;
			$return_str[1] = "";

			return $return_str;
		} else {
			$return_str[0] = substr($str,0,$pos);
			$return_str[1] = substr($str,$pos-1);

			return $return_str;
		}
	}

	function today($sep=".") {
		return date("Y".$sep."m".$sep."d");
	}
	function ntime($sep=":") {
		return date("H".$sep."i".$sep."s");
	}

	///////////////////////////////////////////////////////////////////////////////
	// 함수명 :getFormattedPhone()
	// 내  용 : 전화번호 포멧을 자동변경한다.
	// Event :
	// Object : Input
	///////////////////////////////////////////////////////////////////////////////
	function getFormattedPhone($str) {
		if (strlen($str)<=4) {
			return $str;
		}
		else {
			$len_no1 = strlen($str) - 4;
			$no1 = substr($str,0, $len_no1);
			$no2 = substr($str,$len_no1);
			return $no1 . "-" . $no2;
		}
	}

	///////////////////////////////////////////////////////////////////////////////
	// 함수명 :format_phone()
	// 내  용 : 전화번호 포멧을 자동변경한다.
	// Event :
	// Object : Input
	///////////////////////////////////////////////////////////////////////////////
	function format_phone($str) {
		$rgnNo = Array();
		//String[] rgnNo = new String[22];
		$rgnNo[0] = "02";
		$rgnNo[1] = "031";
		$rgnNo[2] = "032";
		$rgnNo[3] = "033";
		$rgnNo[4] = "041";
		$rgnNo[5] = "042";
		$rgnNo[6] = "043";
		$rgnNo[7] = "051";
		$rgnNo[8] = "052";
		$rgnNo[9] = "053";
		$rgnNo[10] = "054";
		$rgnNo[11] = "055";
		$rgnNo[12] = "061";
		$rgnNo[13] = "062";
		$rgnNo[14] = "063";
		$rgnNo[15] = "064";
		$rgnNo[16] = "010";
		$rgnNo[17] = "011";
		$rgnNo[18] = "016";
		$rgnNo[19] = "017";
		$rgnNo[20] = "018";
		$rgnNo[21] = "019";

		//String eliminateStr = /(\,|\.|\-|\/|\:|\s)/g;
		//str = str.replace(eliminateStr,"");
		$str=str_replace(" ","",$str);
		$str=str_replace("-","",$str);
		for ($i = 0; $i < sizeof($rgnNo); $i++) {
			$pos=strpos($str,$rgnNo[$i]);
			if ($pos !== false && $pos== 0) { 
				$len_rgn = strlen($rgnNo[$i]);
				$formattedNo = util::getFormattedPhone(substr($str,$len_rgn));
				//echo $pos."/".$str."/".$i."/".$len_rgn."/".$formattedNo;
				return $rgnNo[$i] . "-" . $formattedNo;
			}else if(strlen($str)==11){
				$formattedNo = util::getFormattedPhone(substr($str,3));
				return substr($str,0,3) . "-" . $formattedNo;

			}else if(strlen($str)==12){
				$formattedNo = util::getFormattedPhone(substr($str,4));
				return substr($str,0,4) . "-" . $formattedNo;
			}
		}

		if (strlen($str) > 8)
			return $str;

		return util::getFormattedPhone($str);
	}

	// 주민번호로 나이계산
	function calcAge($sno) { 
		$sno1=substr($sno,6,1);
		$sno2=substr($sno,0,2);
		$yr=0;
		$yr = date("Y");
		$sn = $sno2;
		if (($sno1>2 && $sno1<5) || ($sno1>6 && $sno1<9)) {
			
			$yr=$yr-2000-$sn; // 3,4, 7,8
		} else {
			$yr=$yr-1900-$sn; // 1,2, 5,6
		}
		// 5,6,7,8 은 외국인 귀화한 경우
		return $yr;
	}



}	// end util class;
?>
