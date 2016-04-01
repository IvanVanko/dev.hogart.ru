<?
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/php_interface/dbconn.php');

// Create connection

$conn = new mysqli($DBHost, $DBLogin, $DBPassword);


$mysql_error=null;
if ($conn->connect_error) {
$mysql_connection=false;
} else
$mysql_connection=true;
$mysql_status=$conn->connect_error;

$diskSpace=shell_exec("df -h");

$result=array("success"=>"Y","mysql_connection"=>$mysql_connection,"mysql_status"=>$mysql_status,"total_space"=>disk_total_space("/"),"free_space"=>disk_free_space("/"),"detail_space"=>$diskSpace);
echo json_encode($result);
