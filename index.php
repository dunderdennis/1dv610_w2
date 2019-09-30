<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once("controller/PlayGame.php");
require_once("view/HTMLPage.php");
require_once("model/ComputerPlayer.php");
require_once("model/SticksPile.php");
require_once("model/Session.php");


//Setup
session_start();

$session = new \model\Session();

//Load state 
if (!$session->sessionIsSet()) {
	$game = new \model\LastStickGame(new \model\SticksPile(), $session);
} else {
	$game = $session->getSession();
}

$computerPlayer = new \model\ComputerPlayer($game);
$view = new \view\GameView($game, $computerPlayer);
$controller = new controller\PlayGame($game, $view, $computerPlayer);

$controller->handleGameInput();


//Generate output
$page = new view\HTMLPage();
$page->echoPage($view->getHTMLTitle(), $view->getHTMLBody());
