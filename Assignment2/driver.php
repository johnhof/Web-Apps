 <?php 
include 'controller.php';
include 'tableHandler.php';

$controller = new Controller();

echo '<h2 style="margin-left:auto; margin-right:auto;width:225px;">Select Meeting Times</h2>';

//initiates data retrieval, cookie handling, and internal data structure
$controller->initSession();

checkpoint($controller);

//formats data to html
print($controller->getTableHtml());

//display the log data
printLog($controller);

function printLog($controller){
	echo '<h3>'.$controller->getLog().'</h3></br>';
}
function checkpoint($controller){
	if($controller->isFatal()) crashDump($controller);
}

function crashDump($controller){
	printLog();
	$controller->shutdown();
	exit;
}

?>