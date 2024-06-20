<?php
/***
 *写一条真诚的注释可保项目平安——护身符，双手合十，虔诚的祈祷
 */
ini_set("display_errors", "On");
ini_set("error_reporting",E_ALL);
// header("Access-Control-Allow-Origin:*");
header("Content-type: application/json");
header("Access-Control-Allow-Methods:GET");
header("Access-Control-Allow-Headers:x-requested-with, content-type");

function is_conn() {
	$conn = new PDO("mysql:host=localhost;dbname=sb_bbs", "test", "test");
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	return $conn;
}

function is_query($sql) {
	$query = is_conn()->prepare(end($sql));
	array_pop($sql);
	$query->execute($sql);
	$result = $query->fetchAll();
	return json_encode($result,true);
}

function is_insert($sql) {
	$insert = is_conn()->prepare(end($sql));
	array_pop($sql);
	$result = $insert->execute($sql);
	return !empty($result)?$result:"0";
}

switch(array_keys($_GET)[0]) {
	case "latest":
		$sql = Array("SELECT `cid`,`ctitle`,`subjectclass`,`uid` FROM `content` a JOIN user b ON b.`uid`=a.`utag` WHERE `rtag`=0 AND `uid`<>'' ORDER BY a.`cdate` DESC");
		echo(is_query($sql));
	break;
	case "loadall":
		$cid = $_GET["loadall"];
		$sql = Array($cid,$cid,"SELECT `cid`,`ctitle`,`ctext`,`uname`,`uid`,a.`cdate` FROM `content` a JOIN `user` b ON b.`uid`=a.`utag` WHERE `cid`=? OR `rtag`=?");
		echo is_query($sql);
	break;
	case "loadinfo":
		$uname = $_GET["loadinfo"];
		$sql = Array($uname,"SELECT `udepict` FROM `user` WHERE `uname`=?");
		echo is_query($sql);
	break;
	case "login":
		$arr = json_decode($_GET["login"],true);
		$sql = Array($arr['uname'],$arr['upwd'],"SELECT COUNT(*) FROM `user` WHERE `uname`=? AND `upwd`=?");
		echo is_query($sql);
	break;
	case "register":
		$arr = json_decode($_GET["register"],true);
		$sql = Array($arr['uname'],$arr['udepict'],$arr['email'],$arr['upwd'],"INSERT INTO `user` (`uid`, `uname`, `udepict`, `email`, `upwd`,`cdate`) VALUES (NULL,?,?,?,?,DATE_FORMAT(NOW(),'%Y-%m-%d'))");
		echo is_insert($sql);
	break;
	case "create":
		$arr = json_decode($_GET["create"],true);
		$sql = Array($arr['ctitle'],$arr['ctext'],$arr['uname'],$arr['subjectclass'],"INSERT INTO `content` (`cid`, `ctitle`, `ctext`, `cdate`, `rtag`, `utag`, `subjectclass`) VALUES (NULL,?,?,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),0,(SELECT `uid` FROM `user` WHERE `uname`=?),?)");
		echo is_insert($sql);
	break;
	case "send":
		$arr = json_decode($_GET["send"],true);
		$sql = Array($arr['ctext'],$arr['rtag'],$arr['uname'],"INSERT INTO `content` (`cid`, `ctitle`, `ctext`, `cdate`, `rtag`, `utag`, `subjectclass`) VALUES (NULL,'',?,DATE_FORMAT(NOW(),'%Y-%m-%d %H:%i:%s'),?,(SELECT `uid` FROM `user` WHERE `uname`=?),0)");
		echo is_insert($sql);
	break;
}
