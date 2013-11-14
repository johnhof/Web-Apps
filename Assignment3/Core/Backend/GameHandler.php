<?php
session_start(); 


include_once './Helpers.php';
include_once './Core.php';

$req       = getValue('post', 'request');
$forgot    = getValue('post', 'forgot');
$status_in = getValue('session', 'logged_In');
$userEmail = getValue('session', 'email');


if ($status_in && $userEmail) {
  $response = array();

  // return random word and update games played
  if ($req === 'new_game') {

    $result = query('SELECT played from Users where email="'.$userEmail.'"');

    testQuery($result, 'could not retrieve number of games played');

    $playCount = $result[0]['played'];
    $playCount = $playCount + 1;
    
    $result = query('UPDATE Users SET played="'.$playCount.'" where email="'.$userEmail.'"');

    $response['played'] = $playCount;

    $result = query('SELECT word FROM Words ORDER BY RAND() LIMIT 1');
    testQuery($result, 'Could not retrieve a random word');
    
    $response['word'] = $result[0]['word'];
  }

  // return and update wins
  if ($req === 'mark_win') {

    $result = query('SELECT won from Users where email="'.$userEmail.'"');

    testQuery($result, 'could not retrieve number of games won');

    $winCount = $result[0]['won'];
    $winCount = $winCount + 1;
    
    $result = query('UPDATE Users set won="'.$winCount.'" where email="'.$userEmail.'"');

    $response['winCount'] = $winCount;
  }

  // return wins and games played
  if($req === 'get_stats') {

    $result = query('SELECT won from Users where email="'.$userEmail.'"');
    testQuery($result, 'could not retrieve number of games won');

    $response['won'] = $result[0]['won'];

    $result = query('SELECT played from Users where email="'.$userEmail.'"');
    testQuery($result, 'could not retrieve number of games played');

    $response['played'] = $result[0]['played'];
  }
  respond($response);
}

function testQuery($result, $string) {

  if($result) return;

  $response = array();
  $response['error'] = $string;
  $response['query'] = $result;

  respond($response);
}

function respond($response){
  echo json_encode($response);
  exit;
}

?>