<?php

$url = 'ALL.json'
$data = file_get_contents($url);
$characters = json_decode($data);

$csvFileName = 'mtg_card.csv';

$fp = fopen($csvFileName, 'w');

foreach($characters as $row) {
    fputcsv($fp, $row);
}

fclose($fp);