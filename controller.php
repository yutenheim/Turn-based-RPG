<?php

$kick = $_POST[kick];
$block = $_POST[block];

$host='localhost'; 
$database='mmo';
$user='mmo_u';
$pswd='rNkg185@';

define("YOUR_DEFAULT_HP", 35);
define("AIS_DEFAULT_HP", 35);
define("LOW_HP_THRESHOLD", 8);

switch ($kick) {
	case 'kick-in-head':
		$where_kicked = 'в голову';
		break;
	case 'kick-in-chest':
		$where_kicked = 'в корпус';
		break;
	case 'kick-in-legs':
		$where_kicked = 'по ногам';
		break;
	default:
		break;
}
switch ($block) {
	case 'block-head':
		$where_blocked = 'в голову';
		break;
	case 'block-chest':
		$where_blocked = 'в корпус';
		break;
	case 'block-legs':
		$where_blocked = 'по ногам';
		break;
	default:
		break;
}

$conn = mysql_connect($host, $user, $pswd) or die("Fail MySQL.");
mysql_select_db($database) or die("Fail");

$input_attack = array("kick-in-head", "kick-in-chest", "kick-in-legs");
$rand_keys = array_rand($input_attack);

$input_block = array("block-head", "block-chest", "block-legs");
$rand_keys2 = array_rand($input_block);

if ($kick == $input_attack[$rand_keys]) {
	$kick_result = "Противник заблокировал ваш удар " . $where_kicked;
} 
else {
	$kick_power_player = rand(1, 10);
	if ($kick_power_player == 10) {
		$critical_strike_player = "<b style='color: red;'>[critical strike]</b>";
		$kick_power_player = 12;
	}
	$kick_result = "Вы нанесли повреждения противнику " . $where_kicked . " на $kick_power_player HP $critical_strike_player";
	$query = "UPDATE health SET hp=hp-$kick_power_player WHERE name='ai'";
	mysql_query( $query, $conn );
}

if ($block == $input_block[$rand_keys2]) {
	$block_result = "Вы заблокировали удар противника " . $where_blocked;
} 
else {
	$kick_power_ai = rand(1, 10);
	if ($kick_power_ai == 10) {
		$critical_strike_ai = "<b style='color: red;'>[critical strike]</b>";
		$kick_power_ai = 12;
	}
	$block_result = "Противник нанес вам повреждения " . $where_blocked . " на $kick_power_ai HP $critical_strike_ai";
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

if ($read_players_hp <= 0 && $read_ais_hp <= 0) {
	$query5 = "UPDATE health SET hp=".AIS_DEFAULT_HP." WHERE name='ai'";
	mysql_query( $query5, $conn );
	$query6 = "UPDATE health SET hp=".YOUR_DEFAULT_HP." WHERE name='player'";
	mysql_query( $query6, $conn );
	echo " <b>Ничья!</b><br><br><img src='images/draw.jpg'>";
} else if ($read_players_hp <= 0) {
	$query5 = "UPDATE health SET hp=".AIS_DEFAULT_HP." WHERE name='ai'";
	mysql_query( $query5, $conn );
	$query6 = "UPDATE health SET hp=".YOUR_DEFAULT_HP." WHERE name='player'";
	mysql_query( $query6, $conn );
	echo " <b>Вам конец!</b><br><br><img src='images/defeat.jpg'>";
} else if ($read_ais_hp <= 0) {
	$query5 = "UPDATE health SET hp=".AIS_DEFAULT_HP." WHERE name='ai'";
	mysql_query( $query5, $conn );
	$query6 = "UPDATE health SET hp=".YOUR_DEFAULT_HP." WHERE name='player'";
	mysql_query( $query6, $conn );
	echo " <b>Противник повержен, поздравляем!</b><br><br><img src='images/defeat.jpg'>";
} else {
	echo "<br>Понеслась:<br>".$kick_result."<br>".$block_result."<br><br>AI's HP: [".$read_ais_hp."/".AIS_DEFAULT_HP."]<br>Your HP: [".$read_players_hp."/".YOUR_DEFAULT_HP."]";
}
 
?>
