<?php
// include main Doctrine class file
include_once 'Doctrine.php';
spl_autoload_register(array('Doctrine', 'autoload'));
// create Doctrine manager
$manager = Doctrine_Manager::getInstance();
// create database connection
$conn = Doctrine_Manager::connection(
'mysql://telephone:committed@localhost/telephone_db', 'doctrine');
// auto-generate models
Doctrine::generateModelsFromDb('/home/eregiona/public_html/telephone/library/Doctrine/models',
array('doctrine'),
array('classPrefix' => 'Telephone_Model_')
);
?>