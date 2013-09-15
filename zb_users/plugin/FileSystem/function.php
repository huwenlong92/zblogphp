<?php
/**
插件函数
**/
function Get_Filelist($current_path){
	$current_path = iconv('UTF-8','GB2312',$current_path);
	$file_list = array();
	//遍历目录取得文件信息
	if ($handle = opendir($current_path)) {
		while (false !== ($filename = readdir($handle))) {
			if ($filename{0} == '.') continue;
			$file = $current_path . $filename;
			if (is_dir($file)) {
				$file_list['dir'][] = array(
					'has_file' => (count(scandir($file)) > 2), //文件夹是否包含文件
					'filesize' => 0, //文件大小
					'filename' => iconv('GB2312','UTF-8',$filename), //文件名，包含扩展名
					'fileperms' => substr(sprintf('%o', fileperms($current_path . $filename)), -4), //文件权限
					'datetime' => date('Y-m-d H:i:s', filemtime($file)), //文件最后修改时间
				);
			} else {
				$file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
				$file_list['file'][] = array(
					'filesize' => format_size(filesize($file)),//文件大小
					'filetype' => strtolower($file_ext), //文件类别，用扩展名判断); 
					'filename' => iconv('GB2312','UTF-8',$filename), //文件名，包含扩展名
					'fileperms' => substr(sprintf('%o', fileperms($current_path . $filename)), -4), //文件权限
					'datetime' => date('Y-m-d H:i:s', filemtime($file)), //文件最后修改时间
				);
			}
		}
		closedir($handle);
	}
	return $file_list;
}

function format_dir($current_path, $root_path){
	if($current_path == $root_path) return;
	echo '<div><a class="btn btn-mini" href="main.php" title="根目录"><i class="icon-home"></i></a><i class="icon-chevron-right"></i>';
	$current_path = str_replace($root_path, "", $current_path);
	$path_array = explode("/", $current_path);
	array_pop($path_array);
	foreach($path_array as $k => $v){
		$path_url = '';
		for ($i = 0; $i <= $k; $i++) {
			$path_url .= $path_array[$i].'/';
		}
		echo '<a class="btn btn-mini" href="main.php?path='.urlencode($path_url).'"><i class="icon-folder-close"></i>'.$v.'</a>';
		echo '<i class="icon-chevron-right"></i>';
	}

}

function format_size($arg) {
    if ($arg>0){
        $j = 0;
        $ext = array(" Bytes"," KB"," MB"," GB"," TB");
        while ($arg >= pow(1024,$j)) ++$j;
        return round($arg / pow(1024,$j-1) * 100) / 100 . $ext[$j-1];
    } else return "0 Bytes";
}

function GetFileimg($arg) {
    $type_ary = array('acc', 'bat', 'dll', 'doc', 'edit', 'exe', 'hlp', 'jar', 'lnk', 'pdf', 'ppt', 'psd', 'txt', 'xls', 'zba');
	$img_ary = array('png', 'gif',  'jpg',  'jpeg',  'bmp',  'tif',  'ai',  'raw');
	$htm_ary = array('htm',  'html',  'mth',  'xml',  'shtml');
	$code_ary = array('asp',  'aspx',  'php',  'jsp',  'css',  'js', 'sql');
	$tar_ary = array('rar',  'zip',  'tar',  'bzip',  'gzip',  '7z',  'bz',  'bz2',  'bza',  'gzi',  'gz',  'tar.gz', );
	$mov_ary = array('bm', 'rmvb', 'vcd', 'mov', '3gp', 'mpeg', 'wmv', 'flv', 'mp4', 'avi', 'mkv', '', '', '', '', '', '', '', '', '', );
	$msc_ary = array('mp3', 'wma', 'wav', 'mod', 'md', 'cd', 'ape', 'flac');
	
	$ext_array = array('img', 'htm', 'code', 'tar', 'mov', 'msc');
	foreach($ext_array as $v){
		if(in_array($arg, ${$v.'_ary'})) return $v;	
	}
	if(in_array($arg, $type_ary)) {
		return $arg;
	} else {
		return 'no';	
	}
}

function command_panel($current_path, $filename, $bloghost, $blogpath, $isdir, $type){
	$zbsys_file = array('feed.php', 'index.php', 'search.php', 'zb_users', 'zb_system', 'zb_system/cmd.php', 'zb_system/login.php', 'zb_system/function', 'zb_users/language', 'zb_users/plugin', 'zb_users/template', 'zb_users/theme');
	$edit_file = array('asp',  'aspx',  'php',  'jsp',  'css',  'js', 'htm',  'html',  'mth',  'xml',  'shtml', 'sql', 'txt');
	$current_path = iconv('UTF-8', 'GB2312', $current_path.$filename);
	if($isdir){
		foreach($zbsys_file as $v){
			if ($current_path == $v){
				$str = "";
				break;
			}
		}
		if(!isset($str)) $str ="<img src='".$bloghost."zb_system/image/admin/document-rename.png'>&nbsp;&nbsp;<a href='#' onclick=\"del_file('$filename')\"><img src='".$bloghost."zb_system/image/admin/delete.png'></a>";

		return $str;
	
	}else{
		$str = "<a href='#' onclick=\"down_file('$filename')\"><img src='".$bloghost."zb_system/image/admin/download.png'></a>";
		if(in_array($type, $edit_file)) $str = $str."&nbsp;&nbsp;<img src='".$bloghost."zb_system/image/admin/page_edit.png'>";	

		foreach($zbsys_file as $v){
			if ($current_path == $v){
				$sstr = "";
				break;
			}
		}
		if(!isset($sstr)) $str = $str."&nbsp;&nbsp<img src='".$bloghost."zb_system/image/admin/document-rename.png'>&nbsp;&nbsp;<a href='#' onclick=\"del_file('$filename')\"><img src='".$bloghost."zb_system/image/admin/delete.png'></a>";

		return $str;	
	}


	
}


?>