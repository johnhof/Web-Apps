 <?php 
include 'assig1_lib.php';

$controller = new Controller();

if(!$controller->initSession()){
      return;
}