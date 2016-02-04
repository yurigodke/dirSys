<?php 
session_start();

include('_dirSys/class/dirSys.class.php');

$ds = new dirSys();
$ds->addExcludePath('.gitignore');
$ds->addExcludePath('.gitattributes');
$ds->addExcludePath('error_log');
$ds->getFiles();