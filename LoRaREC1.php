<?php
error_reporting(E_ALL);
ini_set ('display_errors', 'On');
include ("login/config.php");

$var1 = file_get_contents('php://input');

//$var = json_decode($json);

$var2 = json_decode($var1);

//echo $var1;

$var3 = "Payload: ";
$var3 .= base64_decode($var2->payload_raw);
$payload2 = base64_decode($var2->payload_raw);
$var3 .= "<br /> Time: ";
$var3 .= $var2->metadata->time;
$var3 .= "<br /> Frequenz: ";
$var3 .= $var2->metadata->frequency;
$var3 .= "<br /> Gateway_id: ";
$var3 .= $var2->metadata->gateways[0]->gtw_id;
$var3 .= "<br /> Channel: ";
$var3 .= $var2->metadata->gateways[0]->channel;
$var3 .= "<br /> RSSI: ";
$var3 .= $var2->metadata->gateways[0]->rssi;
$var3 .= "<br /> SNR: ";
$var3 .= $var2->metadata->gateways[0]->snr;

$time = time();
$frequency = $var2->metadata->frequency;
$gwid = $var2->metadata->gateways[0]->gtw_id;
$channel = $var2->metadata->gateways[0]->channel;
$rssi = $var2->metadata->gateways[0]->rssi;
$SNR = $var2->metadata->gateways[0]->snr;

$payload = base64_decode($var2->payload_raw); 
$data = explode("a", $payload);
$spannung = round(($data[0] * 17)/1000, 3); 
$strom = round((($data[1] * 58.59375)-30000)*-1);
system('echo "'.$spannung.'" > /var/www/th/V');
system('echo "'.$strom.'" > /var/www/th/A');
$timestamp = time();

$eintrag = "INSERT INTO LoRaSolar (time, frequency, gtw_id, channel, rssi, snr, payload, spannung, strom) VALUES ('$timestamp', '$frequency', '$gwid', '$channel', '$rssi', '$SNR', '$payload', '$spannung', '$strom');";
$eintragen = mysqli_query($db, $eintrag);

$var3 .= mysql_error();

//$var1 = $var->payload_raw;

$handle = fopen ("loradata.html", "w");
fwrite ($handle, "$spannung ; $strom");
fwrite ($handle, $var3);
fclose ($handle);
?>
