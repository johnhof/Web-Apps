 <?php 
  include_once './Backbone/Controller.php';
  include_once './Backbone/Model.php';
  include_once './Backbone/View.php';

  $controller = new Controller();
  $model = new Model();
  $view = new View();

// Please specify your Mail Server - Example: mail.example.com.
ini_set("SMTP","pitt.edu");

// Please specify an SMTP Number 25 and 8889 are valid SMTP Ports.
ini_set("smtp_port","25");

// Please specify the return address to use
ini_set('sendmail_from', 'example@YourDomain.com');

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