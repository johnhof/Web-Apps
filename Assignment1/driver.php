 <?php 
include 'controller.php';

$controller = new Controller();

echo '<h2 style="margin-left:auto; margin-right:auto;width:225px;">Select Meeting Times</h2>';

$controller->initSession();

checkpoint($controller);

print($controller->getTableHtml());


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