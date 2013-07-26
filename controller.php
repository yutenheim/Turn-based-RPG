<?php

$kick = $_POST[kick];
$block = $_POST[block];

$host='xxx'; 
$database='xxx';
$user='xxx';
$pswd='xxx';

define("YOUR_DEFAULT_HP", 35);
define("AIS_DEFAULT_HP", 35);

$conn = mysql_connect($host, $user, $pswd) or die("Fail MySQL.");
mysql_select_db($database) or die("Fail");

$input_attack = array("kick-in-head", "kick-in-chest", "kick-in-legs");
$rand_keys = array_rand($input_attack);

$input_block = array("block-head", "block-chest", "block-legs");
$rand_keys2 = array_rand($input_block);

if ($kick == $input_attack[$rand_keys]) {
	$kick_result = "Противник заблокировал ваш удар.";
} 
else {
	$kick_power_player = rand(1, 10);
	if ($kick_power_player == 10) {
		$critical_strike_player = "<b style='color: red;'>[critilcal strike]</b>";
	}
	$kick_result = "Вы нанесли повреждения противнику на $kick_power_player HP $critical_strike_player";
	$query = "UPDATE health SET hp=hp-$kick_power_player WHERE name='ai'";
	mysql_query( $query, $conn );
}

if ($block == $input_block[$rand_keys2]) {
	$block_result = "Вы заблокировали удар противника.";
} 
else {
	$kick_power_ai = rand(1, 10);
	if ($kick_power_ai == 10) {
		$critical_strike_ai = "<b style='color: red;'>[critilcal strike]</b>";
	}
	$block_result = "Противник нанес вам повреждения на $kick_power_ai HP $critical_strike_ai";
	$query2 = "UPDATE health SET hp=hp-$kick_power_ai WHERE name='player'";
	mysql_query( $query2, $conn );
}

$query3 = "SELECT hp FROM health WHERE name='player'";   
$result = mysql_query ($query3);
$row = mysql_fetch_array($result);
$read_players_hp = $row[0];

$query4 = "SELECT hp FROM health WHERE name='ai'";   
$result2 = mysql_query ($query4);
$row2 = mysql_fetch_array($result2);
$read_ais_hp = $row2[0];

if ($read_players_hp <= 0) {
	$query5 = "UPDATE health SET hp=".AIS_DEFAULT_HP." WHERE name='ai'";
	mysql_query( $query5, $conn );
	$query6 = "UPDATE health SET hp=".YOUR_DEFAULT_HP." WHERE name='player'";
	mysql_query( $query6, $conn );

	echo " <b>Вам конец!</b>";
} 

if ($read_ais_hp <= 0) {
	$query5 = "UPDATE health SET hp=".AIS_DEFAULT_HP." WHERE name='ai'";
	mysql_query( $query5, $conn );
	$query6 = "UPDATE health SET hp=".YOUR_DEFAULT_HP." WHERE name='player'";
	mysql_query( $query6, $conn );
	echo " <b>Противник повержен, поздравляем!</b>";
} 
else {
	echo "<br>Понеслась:<br>".$kick_result."<br>".$block_result."<br><br>AI's HP: [".$read_ais_hp."/".AIS_DEFAULT_HP."]<br>Your HP: [".$read_players_hp."/".YOUR_DEFAULT_HP."]";
}
 
?>