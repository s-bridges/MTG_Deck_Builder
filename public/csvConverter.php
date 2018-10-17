<?php

$url = 'ALL - Copy.json'
$data = file_get_contents($url); # doesn't work...
$characters = json_decode($data);

$csvFileName = 'mtg_card.csv';

$fp = fopen($csvFileName, 'w');

foreach($characters as $row) {
    fputcsv($fp, $row);
}

fclose($fp);