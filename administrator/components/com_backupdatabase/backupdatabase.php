<?php
/*------------------------------------------------------------------------
# mod_backupdatabase - Backup Database
# ------------------------------------------------------------------------
# Iacopo Guarneri
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.the-html-tool.com
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 

//error_reporting(E_ALL);
//ini_set("display_errors", 1);

$app = JFactory::getApplication();
$host=$app->getCfg('host');
$user=$app->getCfg('user');
$password=$app->getCfg('password');
$database=$app->getCfg('db');
$dir="components/com_backupdatabase/backup";

mysql_connect($host,$user,$password);
@mysql_select_db($database) or die("Connection error");
echo "if the export problems that controls the php functions:<br />
system and mysqldump<br />
are supported by your server<br /><br />";

JToolBarHelper::title("Backup Database");

if(!file_exists($dir)){
	Mkdir($dir,0775);
	$handle = fopen($dir."/index.html", 'w');
	fwrite($handle,"");
	fclose($handle);
}

function elencafiles($dirname){
	$arrayfiles=Array();
	if(file_exists($dirname)){
		$handle = opendir($dirname);
		while (false !== ($file = readdir($handle))) { 
			if(is_file($dirname.$file)){
				array_push($arrayfiles,$file);
			}
		}
		$handle = closedir($handle);
	}
	sort($arrayfiles);
	return $arrayfiles;
}
function eliminafiles($dirname){
	if(file_exists($dirname) && is_file($dirname)) {
		unlink($dirname);
	}elseif(is_dir($dirname)){
		$handle = opendir($dirname);
		while (false !== ($file = readdir($handle))) { 
			if(is_file($dirname.$file) && $file!="index.html"){
				unlink($dirname.$file);
			}
		}
		$handle = closedir($handle);
		//rmdir($dirname);
	}
}

if(@$_GET['ex']==2){
	$bk = $dir."/backup".date("j-n-Y:G-i-s").".sql";
	system("mysqldump --user=$user --password=$password --host=$host $database > $bk",$res);
}
if(@$_GET['ex']==1){
	eliminafiles($dir."/");
}

echo "<a href='index.php?option=com_backupdatabase&ex=2'>Start backups</a> - <a href='index.php?option=com_backupdatabase&ex=1'>Empties the folder</a><br /><br />";

$arrayfile=array();
$arrayfile=elencafiles($dir."/");
foreach($arrayfile as $file){
	if($file!="index.html")
	echo "<a href='".$dir."/".$file."'>".$file."</a><br />";
}
?> 
