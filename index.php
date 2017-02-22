<?php
session_start();

include('_dirSys/class/dirSys.class.php');

$ds = new dirSys();
$ds->addExcludePath([
  '.gitignore',
  '.git',
  '.gitattributes',
  'error_log'
]);
$ds->getFiles();
