<?php
class util
{

	/*
                      Class Name util (PHP 4 >= 4.3.0, PHP 5)
 -------------------------------------------------------------------------------------------------------------
 *  Source Path		: /include/util.php (PHP 4 >= 4.3.0, PHP 5)
 *  �ý��۸�		: 
 * �������߼Һз�	:
 * ���α׷�����		: utility ���̺귯��
 * ���ϸ�			: util.php
 * Called By		: All
 * Calling			:
 *  �ۼ���			: Ȳ����
 *  �ۼ���¥		: 2008. 11. 01
 * ----------------------------------------------------------------------
 * �������
 * ��������	    ������		    ����
 * -------		-----			------------------------------
 * 2008.11.01	Ȳ����			���� ����

--------���� ����-----------------------------------------------------------
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
	// Ư�� URL�� �̵�
	function sendRedirect($msg,$url)
	{
		if (strlen($msg) == 0) {
			echo ("<script language='javascript'>location.href='$url'</script>");
		} else {
			echo ("<script language='javascript'>alert(\"$msg\")</script>");
			echo ("<script language='javascript'>location.href='$url'</script>");
		}
	}	// end sendRedirect

	// Ư�� URL�� top page �̵�
	function sendTopRedirect($msg,$url)
	{
		if (strlen($msg) == 0) {
			echo ("<script language='javascript'>top.location.href='$url'</script>");
		} else {
			echo ("<script language='javascript'>alert(\"$msg\")</script>");
			echo ("<script language='javascript'>top.location.href='$url'</script>");
		}
	}	// end sendRedirect

	// ALERT�� �̿��� �޽��� ���
	function msg($msg)
	{
		echo ("<script language='javascript'>alert(\"$msg\")</script>");
	}


	// ���� �޽��� ���
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
		$link_str .="&nbsp;<font size=2>�� ���ڵ�=$total_Rec / �� ������=$total_Page</font>";
		return $link_str;


	}

	//������ �̵�
	function Page()
	{
		global $page,$total_page,$page_per_block,$first_page,$last_page,$total_record,$last;
		$bbsdir=""; //"/php"; // 2008/09/20

		##### ������������ ������ ��� ������������ ���� ��ũ�� Ȱ��ȭ��Ų��.
		if ($page > 1) {
			$page_num = $page - 1;
			echo ("<a href=\"javascript:Page('$page_num')\"><img src=\"$bbsdir/btn/prevo.gif\" align=absmiddle></a>&nbsp;");
		} else {
			echo ("<img src=\"$bbsdir/btn/prev.gif\" align=absmiddle>");
		}

		echo "&nbsp;";

		##### �Խù� ��� �ϴ��� �� �������� ���� �̵��� �� �ִ� ������ ��ũ�� ���� ������ �Ѵ�.
		$total_block = ceil($total_page/$page_per_block);
		$block = ceil($page/$page_per_block);

		$first_page = ($block-1)*$page_per_block;
		$last_page = $block*$page_per_block;

		if($total_block <= $block) {
			$last_page = $total_page;
		}

		##### ������������Ͽ� ���� ������ ��ũ
		if($block > 1) {
			$my_page = $first_page;
			echo("<a href=\"javascript:Page('$my_page')\">[���� ${page_per_block}��]</a>&nbsp;&nbsp;&nbsp;");
			//echo("<a href=\"javascript:Page('$my_page')\"><img src='admin/img/board_prev02.gif' border='0'>");
		}

		##### ������ ������ ��Ϲ��������� �� �������� �ٷ� �̵��� �� �ִ� �����۸�ũ�� ����Ѵ�.
		for($direct_page = $first_page+1; $direct_page <= $last_page; $direct_page++) {
			echo "&nbsp;";
			if($page == $direct_page) {
				echo("<font color=#FF6600><b>$direct_page</b></font>");
			} else {
				echo("<a href=\"javascript:Page('$direct_page')\">$direct_page</a>");
			}
			echo "&nbsp;";
		}

		##### ������������Ͽ� ���� ������ ��ũ
		if($block < $total_block) {
			$my_page = $last_page+1;
			echo("&nbsp;&nbsp;&nbsp;<a href=\"javascript:Page('$my_page')\">[���� ${page_per_block}��]</a>");
			//echo("<a href=\"javascript:Page('$my_page')\"><img src='/admin/img/board_next02.gif' border='0'></a>");
		}

		echo "&nbsp;";

		##### �Խù��� �������������� ������ ��� ������������ ���� ��ũ�� Ȱ��ȭ��Ų��.
		if ($total_record > $last) {
			$page_num = $page + 1;
			echo ("<a href=\"javascript:Page('$page_num')\"><img src=\"$bbsdir/btn/nexto.gif\" align=absmiddle></a>&nbsp;");
		} else {
			echo ("<img src=\"$bbsdir/btn/next.gif\" align=absmiddle>");
		}
	}//end Page


	// ��ȭ��ȣ�� �����·� ���� (����� ��ȭ��ȣ ���� �ִ� 'X' ���ڸ� �����Ѵ�.)
	function getTel($tel) {
		$telStr[0] = str_replace("X","",substr($tel,0,4));
		$telStr[1] = str_replace("X","",substr($tel,4,4));
		$telStr[2] = str_replace("X","",substr($tel,8,4));

		return $telStr;
	}


/*
###########################################################################################################
##########
##########	���� ���� �Լ�
##########
###########################################################################################################
*/
	/*
	* ### ���ε� ���� ###
	* POST ������� ���ε�� ������ ������ �����Ѵ�.
	* @param value				POST������� ���۵� ���ϸ�
	* @param dir				������ ������ DIR
	* @param file_size			���ε� ������ �ִ� �뷮
	* @param extension_check	���ε尡 �Ұ����� ���� Ȯ����(�迭)
	* @return
	* ### Example ###
	* EX   : fileUpload("image.jpg","/image","5",array);
	* ��� : true
	*/
	function fileUpload($value,$dir,$file_size,$extension_check)
	{
		//������ ����� DIR
		$save_dir = $_SERVER["DOCUMENT_ROOT"] . $dir . DIRECTORY_SEPARATOR;

		//�������� �������� �˻� �Ѵ�.
		if (is_uploaded_file($_FILES[$value]['tmp_name']) && $_FILES[$value]['size'] > 0 ) {
			//dir�� ���� �ϴ��� Ȯ���ϴ�.
			if (!is_dir($save_dir)) {
				util::msg("�������� �ʴ� DIR �Դϴ�.");
				return;
			}

			//Ȯ���ڸ� ������ ���� �̸�(�ӽ�)
			$file_name_tmp = ereg_replace("(\.[^\.]*$)","",$_FILES[$value]['name']);

			//�����̸��� ������ Ȯ����(�ӽ�)
			$extension_tmp = str_replace($file_name_tmp, "" , $_FILES[$value]['name']);


			//���ε� �Ҽ� ���� ������ üũ �Ѵ�.
			if ($extension_check != "" || is_array($extension_check)) {
				//���ε� �Ҽ� ���� ���� üũ
				$extension_check_tmp = strtolower(str_replace(".","",$extension_tmp));

				if (is_array($extension_check)) {
					if (in_array($extension_check_tmp, $extension_check)) {
						util::msg("���ε� �Ҽ� ���� ���� Ȯ���� �Դϴ�!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				} else {
					if (strcasecmp($extension_check_tmp,$extension_check) == 0) {
						util::msg("���ε� �Ҽ� ���� ���� Ȯ���� �Դϴ�!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				}
			}

			//������ �뷮 üũ
			if ( ($_FILES[$value]['size']) > ($file_size * 1024 * 1024) ) {
				util::msg("���ε� �뷮�� �ʰ� �Ǿ����ϴ�\\n���ε� �ִ� �뷮�� $file_size MB �Դϴ�.");
				unlink($_FILES[$value]['tmp_name']);
				return;
			}


			//���ε�� ���� ���
			$check_file = $save_dir . strtolower($_FILES[$value]['name']);

			//���ε�� �����߿� ���� �̸��� ������ �ִ��� üũ
			$i = 1;
			while (file_exists($check_file) == true) {
				$check_file = $save_dir . strtolower($file_name_tmp . "_" . $i . $extension_tmp);
				$i++;
			}

			//������ �����Ѵ�.
			if (!copy($_FILES[$value]['tmp_name'], $check_file)) {
				util::msg("������ ���ε��ϴµ� ���� �߽��ϴ�!");
				return;
			}

			//�ӽ�Dir�� �����ߴ� ������ �����.
			if (!@unlink($_FILES[$value]['tmp_name'])) {
				util::msg("�ӽ������� ���� �ϴµ� ���� �Ͽ����ϴ�!");
			}


			//��θ��� �����̸��� ��ȯ�մϴ�.
			$fileName = basename($check_file);
		}

		return $fileName;
	}//end fileUpload


	function fileUpload2($value,$dir,$file_size,$extension_check)
	{
		//������ ����� IDR
		$save_dir = $_SERVER["DOCUMENT_ROOT"] . $dir . DIRECTORY_SEPARATOR;

		//�������� �������� �˻� �Ѵ�.
		if (is_uploaded_file($_FILES[$value]['tmp_name']) && $_FILES[$value]['size'] > 0 ) {

			//dir�� ���� �ϴ��� Ȯ���ϴ�.
			if (!is_dir($save_dir)) {
				util::msg("�������� �ʴ� DIR �Դϴ�.");
				return;
			}

			//Ȯ���ڸ� ������ ���� �̸�(�ӽ�)
			$file_name_tmp = ereg_replace("(\.[^\.]*$)","",$_FILES[$value]['name']);

			//�����̸��� ������ Ȯ����(�ӽ�)
			$extension_tmp = str_replace($file_name_tmp, "" , $_FILES[$value]['name']);


			//���ε� �Ҽ� ���� ������ üũ �Ѵ�.
			if ($extension_check != "" || is_array($extension_check)) {

				//���ε� �Ҽ� ���� ���� üũ
				$extension_check_tmp = strtolower(str_replace(".","",$extension_tmp));

				if (is_array($extension_check)) {
					if (in_array($extension_check_tmp, $extension_check)) {
						util::msg("���ε� �Ҽ� ���� ���� Ȯ���� �Դϴ�!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				} else {
					if (strcasecmp($extension_check_tmp,$extension_check) == 0) {
						util::msg("���ε� �Ҽ� ���� ���� Ȯ���� �Դϴ�!");
						unlink($_FILES[$value]['tmp_name']);
						return;
					}
				}
			}

			//������ �뷮 üũ
			if ( ($_FILES[$value]['size']) > ($file_size * 1024 * 1024) ) {
				util::msg("���ε� �뷮�� �ʰ� �Ǿ����ϴ�\\n���ε� �ִ� �뷮�� $file_size MB �Դϴ�.");
				unlink($_FILES[$value]['tmp_name']);
				return;
			}


			//���ε�� ���� ���
			//$check_file = $save_dir . strtolower($_FILES[$value]['name']);
			$check_file = $save_dir . time() . strtolower($extension_tmp);

			//���ε�� �����߿� ���� �̸��� ������ �ִ��� üũ
			/*
			$i = 1;
			while (file_exists($check_file) == true) {
				$check_file = $save_dir . strtolower( time() . "_" . $i . $extension_tmp) ;
				$i++;
			}
			*/

			//������ �����Ѵ�.
			if (!copy($_FILES[$value]['tmp_name'], $check_file)) {
				util::msg("������ ���ε��ϴµ� ���� �߽��ϴ�!");
				return;
			}

			//�ӽ�Dir�� �����ߴ� ������ �����.
			if (!@unlink($_FILES[$value]['tmp_name'])) {
				util::msg("�ӽ������� ���� �ϴµ� ���� �Ͽ����ϴ�!");
			}


			//��θ��� �����̸��� ��ȯ�մϴ�.
			$fileName = basename($check_file);
		}

		return $fileName;
	}//end fileUpload2


	/*
	* ### Dir ���� �Լ� ###
	* @param save_dir    ����Ȱ Dir ��[array]
	* @return
	* ### Example ###
	* EX   : dir_check(array("/dir_name", "/dir_name/dir_name");
	* ��� : true
	*/
	function dir_check($save_dir)
	{

		//������ �޽���
		$new_array = array();

		for ($i=0;$i<sizeof($save_dir);$i++)
		{
			$get_dir[$i] = $save_dir[$i];
			$dir_check[$i] = $_SERVER["DOCUMENT_ROOT"] . $get_dir[$i];


			//dir ���� ���� üũ, dir�� ������ dir ����;
			if (!is_dir($dir_check[$i])) {
				if (!@mkdir($dir_check[$i],0777)) {
					util::errormsg("������ ���� �� Dir($dir_check[$i]) �� ���� �Ҽ� �����ϴ�!");
					exit();
				}
			}


			/*
			//�۹̼� ����
			if (!@chmod($dir_check[$i],0777)) {
				util::errormsg("$dir_check[$i] �� �۹̼��� ���� �� �� �����ϴ�!");
				exit();
			}

			//������ �۹̼� üũ
			$file_perms[$i] = substr(sprintf("%o", fileperms($dir_check[$i])), -3);

			//�۹̼� üũ
			if ($file_perms[$i] != 777) {
				util::errorMsg("$dir_check[$i] �۹̼��� �ùٸ��� �ʽ��ϴ�!<br>�۹̼��� 777�� �����Ͽ� �ּ���!");
				exit();

			}
			*/

		}//end for

		return;
	}//end dir_check;


	function perms_check($fileStr) {
		//������ �޽���
		$new_array = array();

		//������ ���� �ϴ��� �˻�
		if (!file_exists($fileStr)) {
			//$this->errorMsg("$fileStr ������ ���� ���� �ʽ��ϴ�!",1);
			$r_msg = array_push($new_array,$fileStr . "������ ���� ���� �ʽ��ϴ�!");
		}

		//�۹̼� ����
		if (!@chmod($fileStr,0777)) {
			//$this->errorMsg("$fileStr �� �۹̼��� ���� �� �� �����ϴ�!",1);
			$r_msg .= array_push($new_array,$fileStr . "�� �۹̼��� ���� �� �� �����ϴ�!");
		}

		//������ �۹̼� üũ
		$file_perms = substr(sprintf("%o", fileperms($fileStr)), -3);

		//�۹̼� üũ
		if ($file_perms != 777) {
			//$this->errorMsg("$fileStr �۹̼��� �ùٸ��� �ʽ��ϴ�!\\n�۹̼��� 777�� �����Ͽ� �ּ���!",1);
			$r_msg .= array_push($new_array,$fileStr . "�۹̼��� �ùٸ��� �ʽ��ϴ�!. �۹̼��� 777�� �����Ͽ� �ּ���!");
		}

	}	// end perms_check


	/*
	* ### ������ ����� Ȯ�� �Ѵ�. ###
	* @param file	���ϸ�
	* @return
	* ### Example ###
	* EX   : filesize("sample.html")
	* ��� : array[0] = �����̸�, array[1] = ���� ������, array[2] = ������ ����
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
##########	�̹��� ���� �Լ�
##########
###########################################################################################################
*/
	/*
	* ### ������ �̹��� ���� Ȯ���Ѵ�.(������ �̹��� �̸� true, �ƴϸ� false ��ȯ�Ѵ�) ###
	* @param file    ���ϸ�
	* @return
	* ### Example ###
	* EX   : is_image("image.jpg")
	* ��� : true
	*/
	function is_image($file)
	{
		if (file_exists($file) == true && function_exists('getimagesize')) {
			//�̹��� ������ �� ������ �迭�� �����Ѵ�.
			$image_type = @GetImageSize($file);

			//�̹��� ������ ��ȯ�Ѵ�.
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
	* ### �̹��� type�� ��ȯ�Ѵ�. ###
	* @param file	���ϸ�
	* @return
	* ### Example ###
	* EX   : is_image_type("image.jpg")
	* ��� : jpg
	*/
	function image_type($file)
	{
		if (file_exists($file) == true && function_exists('getimagesize')) {

			//�̹��� ������ �� ������ �迭�� �����Ѵ�.
			$image_type = @GetImageSize($file);

			//�̹��� ������ �����Ѵ�.
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
	* ### �̹��� ũ�� �����ϱ� ###
	* @param file		ũ�⸦ ������ �̹���
	* @param save_dir	����� �̹����� ����� DIR
	* @param w			����� �̹����� Width
	* @param h			����� �̹����� Height
	* @param name		����� �̹����� ���� �̸�
	* @return
	* ### Example ###
	* EX   : resize_image("imagetest.jpg,"/image",100,100,"resize")
	* ��� : imagetest_resize.jpg
	*/
	function resize_image($file, $save_dir, $w, $h, $name)
	{
		//������ ���� �ϴ��� Ȯ���Ѵ�.
		if (file_exists($file) == true) {
			if (util::image_type($file) == "jpg" || util::image_type($file) == "gif") {

				//�̹��� ������ �� ������ �����Ѵ�.
				$img_size = @GetImageSize($file);

				//���ο� �����÷� �̹����� �����Ѵ�.
				$dst_image = ImageCreateTrueColor($w,$h);

				//Ȯ���ڸ� ������ ���� �̸�
				$file_name = ereg_replace("(\.[^\.]*$)","", basename($file));

				//�����̸��� ������ Ȯ����
				$extension = str_replace($file_name, "" , basename($file));

				//ũ�Ⱑ ����� �̹��� ���
				$save_file = $save_dir . "/" .  strtolower($file_name . "_" . $name . $extension);

				//���� �̸��� �̹����� ������� �̹��� �̸� ����
				$i = 1;
				while (file_exists($save_file) == true) {
					$save_file = $save_dir . "/" .  strtolower($file_name . "_" . $name . "_" . $i . $extension);
					$i++;
				}

				//�̹��� Ÿ���� jpg �̸�...
				if (util::image_type($file) == "jpg") {

					//���ο� JPG ������ �����Ѵ�.
					$image = ImageCreateFromJpeg($file);

					//�� �̹������� �����̹����� �׸���.
					ImagecopyResampled($dst_image, $image, 0, 0, 0, 0, $w, $h, $img_size[0], $img_size[1]);

					//JPG�̹����� �����Ѵ�.
					ImageJpeg($dst_image, $save_file, 100);

					//�޸� ��ȯ
					ImageDestroy($dst_image);

					//��θ��� �����̸��� ��ȯ�մϴ�.
					return basename($save_file);

				} else if (util::image_type($file) == "gif") {

					//���ο� GIF ������ �����Ѵ�.
					$image = ImageCreateFromGif($file);

					//�� �̹������� �����̹����� �׸���.
					ImagecopyResampled($dst_image, $image, 0, 0, 0, 0, $w, $h, $img_size[0], $img_size[1]);	
					
					//GIF �̹����� ���� �Ѵ�.
					ImageGif($dst_image, $save_file, 100);

					//�޸� ��ȯ
					ImageDestroy($dst_image);

					//��θ��� �����̸��� ��ȯ�մϴ�.
					return basename($save_file);
				}

			}//end if

		}// end if ##file_exists($file) == true

	}	// end resize_image

	// �׸� ������
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
##########	���ڿ� ���� �Լ�
##########
##########################################################################################################
*/
	//���ڿ� �ڸ���
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
	* ### ���ڿ� �ڸ��� ###
	* @param msg		����
	* @param cut_size	�ڸ� ���ڿ� ����
	* @param last		���ڿ��� �ڸ��� ���ڿ� �ڿ� ���� ����
	* @return
	* ### Example ###
	* EX   : sub_mbstring("�ȳ��ϼ���",3,"...")
	* ��� : �ȳ���...
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
	// ���ڿ� ���� (�̻��� �����϶��� ... �� ǥ��)
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
	* ### ���ڿ��� null ���� Ȯ�� �Ѵ�###
	* @param msg		����
	* @return
	* ### Example ###
	* EX   : is_null("�ȳ��ϼ���")
	* ��� : false
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
	 * url �� ���� Ư�� parameter �� �ش��ϴ� ���� ������ �Ŀ� url �� ��ȯ�Ѵ�.
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
	* ### �������� ��Ÿ�� ���ڿ��� ã�� �迭�� �����Ѵ�. ###
	* @param str    ���ڿ�
	* @param search �O�� ����
	* @return Array
	* ### Example ###
	* EX   : str_pos("abcde.abc", ".");
	* ��� : array[0] = abcde, array[1] = abc
	*/
	function str_pos($str,$search)
	{
		$pos = strrpos($str, $search);
		$return_str = array();
		if ($pos === false) { // ����: ��ȣ 3��
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
	// �Լ��� :getFormattedPhone()
	// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
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
	// �Լ��� :format_phone()
	// ��  �� : ��ȭ��ȣ ������ �ڵ������Ѵ�.
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

	// �ֹι�ȣ�� ���̰��
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
		// 5,6,7,8 �� �ܱ��� ��ȭ�� ���
		return $yr;
	}



}	// end util class;
?>
