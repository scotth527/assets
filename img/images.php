<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$images = array();
const BASE_PATH = './';
$images = createDirectory(BASE_PATH, $images);

function createDirectory($path,$imgs){
	$directories = scandir($path);
	$urlparts = explode("/", $path);
	$level = 0;
	foreach ($directories as $value) {
		$newPath = $path.$value.'/';
		//echo "Recorriendo: $newPath \n";
		if($value!='.' and $value!='..' and is_dir($path)) 
		{
			$laspath = basename($path);
			//echo "entro...$laspath... \n";
			//if(isset($urlparts[5])) echo $urlparts[5]."\n";
			if(is_dir($newPath)) $imgs = createDirectory($newPath,$imgs);
			$auxImg = array(
					"ext" => isImg($value),
					"category" => basename($path), 
					"name" => $value, 
					"url" => $path.$value
				);
			$auxImg = filterImage($auxImg);
			if($auxImg) array_push($imgs, $auxImg);
		}
		//else echo "No es directorio".$newPath."\n";
	}
	return $imgs;
}

function filterImage($img)
{
	if(!$img['ext']) return false;
	if($img['category']=='.') return false;
	if(isset($_GET['cat']) and $img['category']!=$_GET['cat']) return false;
	
	$tags = explode('.',$img['name']);
	array_pop($tags);
	$img['tags'] = $tags;
	
	if(isset($_GET['tags']))
	{
		foreach($_GET['tags'] as $t) if(in_array($t,$tags)) return $img;
		return false;
	}
	
	return $img;
}

function isImg($path){
	$imageExtensions = array("png","jpg","jpeg","gif","ico");
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	
	if(in_array($ext,$imageExtensions)) return $ext;
	else return false;
}

function printFile($files){

    if ($files and file_exists($files['url'])) {

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: ".mime_content_type($files['url']));
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($files['url']));
        echo file_get_contents($files['url']);
        die();
    } else {
        die("Error: File not found.");
    } 
}

function printJSON($content){
	if(!$content) $content = array();
	header("Content-type: application/json"); 
	echo json_encode($content);
	die();
}

function printResult($result)
{
	if(isset($_GET['blob'])) printFile($result);
	else printJSON($result);
}

if(isset($_SERVER['HTTP_REFERER']) and (strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "4geeksacademy"))){
	header("Access-Control-Allow-Origin: *");
}

if(count($images)==0) printResult(null);

$result = $images;
if(isset($_GET['random'])) $result = $images[rand(0,count($images)-1)];
printResult($result);
