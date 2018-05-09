<?php
/**
 * Created by PhpStorm.
 * User: Takdzwiedz
 * Date: 19.04.2018
 * Time: 22:52
 */

include "class/Depot.php";


$order = new Depot();
$order->store(1000, 2.5);
$order->showAvail();
$order->pull(700);
$order->showAvail();
$order->showPrice();
$order->store(200, 2.4);
$order->showAvail();
$order->store(1000, 2.3);
$order->showAvail();
$order->pull(1000);
$order->showAvail();
$order->showPrice();

