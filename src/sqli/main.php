<?php
require 'Building.php';
require 'Elevator.php';
$elevator1 = new \Sqli\Elevator('1',0);
$elevator2 = new \Sqli\Elevator('2' , 4 , 'UP');
$elevator3 = new \Sqli\Elevator('3' , 5);
$building = new \Sqli\Building(10 ,[$elevator1 , $elevator2]);
$building->requestElevator(2);
