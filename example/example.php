<?php

require __DIR__ . '/../vendor/autoload.php';

$json = json_decode(file_get_contents(__DIR__ . '/mjml.json'), true);

$json2mjml = new \Json2Mjml\Json2Mjml();


echo($json2mjml->json2xml($json));