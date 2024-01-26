<?php
$kasutaja='maksymmiskevych';
$serverinimi='localhost';
$parool='123456';
$andmebaas='maksymmiskevych';
$yhendus=new mysqli($serverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset('UTF8');