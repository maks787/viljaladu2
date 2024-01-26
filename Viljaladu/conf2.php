<?php
$kasutaja='d123182_maksym';
$serverinimi='d123182.mysql.zonevs.eu';
$parool='9655007z22112006';
$andmebaas='d123182_maksymdb';
$yhendus=new mysqli($serverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset('UTF8');
