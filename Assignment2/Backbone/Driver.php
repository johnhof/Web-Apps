 <?php 
include_once './Backbone/Controller.php';
include_once './Backbone/Model.php';
include_once './Backbone/View.php';

$controller = new Controller();
$model = new Model();
$view = new View();

  if($model->isHomeRequest()){
  	println('home');	
  	if($model->loggedIn()){
  		println('logged in');
    	$model->setHomeView();
	}
	else {
		println('not logged in');
		$model->setLoginView();
	}
  }
  else {
  	println('retrieving schedule');
  	$model->setScheduleView();
  }

?>