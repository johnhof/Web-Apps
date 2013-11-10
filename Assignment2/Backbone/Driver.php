 <?php 
  include_once './Backbone/Controller.php';
  include_once './Backbone/Model.php';
  include_once './Backbone/View.php';

  $controller = new Controller();
  $model = new Model();
  $view = new View();

  if($model->isMakerRequest()){
    if($model->loggedIn()){
      $model->setMakerView();
    }
  	else {
  	  $model->setLoginView();
    }
  }
  else {
  	$model->setScheduleView();
  }

  $view = $model->getView();
  $view->generate();

  $view->displayHtml();

?>