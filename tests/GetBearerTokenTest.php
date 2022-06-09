<?php

use Appiersign\PeoplePay\PeoplePay;

require __DIR__ . '/../vendor/autoload.php';

$peoplePay = new PeoplePay('http://3.15.188.18:9000', 'f202925d-36e9-49e1-9649-af11e87f13d3', '62976b446449d8d3b9708b94');

//var_dump($peoplePay->getBearerToken());
//var_dump($peoplePay->collect());
//var_dump($peoplePay->getAccountName('0249621938', 'mtn'));
//var_dump($peoplePay->checkStatus('62a0c3ef7e1d592339af0582'));
var_dump($peoplePay->checkStatus('62a1dbda912918f88e75547a'));