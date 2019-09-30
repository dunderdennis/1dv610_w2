<?php

namespace view;


class GameView  {
	// const STARTING_NUMBER_OF_STICKS = 22;
	private static $DRAW_GET_INDEX = "draw";
	private static $STARTOVER_GET_INDEX = "start";
	

	private $game;
	private $computerPlayer;
	private $errorMessage = "";


	public function __construct(\model\LastStickGame $game, \model\ComputerPlayer $computer) {
		$this->game = $game;
		$this->computerPlayer = $computer;
	}

	//Action
	public function playerSelectedSticks() : bool {
		if (isset($_GET[self::$DRAW_GET_INDEX])) {
			try {
				$this->getSelection(); //this one throws exception if the input is wrong
				return true;
			} catch (\model\InvalidSticksSelectionException $e) {
				$this->errorMessage = "Not valid input, please select one of the three buttons below";
			}
		}
		return false;
	}

	public function getSelection() : \model\StickSelection {

		if (is_numeric($_GET[self::$DRAW_GET_INDEX]) ) {
			$amount = $_GET[self::$DRAW_GET_INDEX];

			if ($amount < \model\StickSelection::getMinSelection() || $amount > \model\StickSelection::getMaxSelection())
				throw new \model\InvalidSticksSelectionException();
			return new \model\StickSelection($amount);
		}

		throw new \model\InvalidSticksSelectionException("Not a number");
	}

	



	public function getHTMLTitle() : string {
		return "Game of sticks";
	}

	public function getHTMLBody() : string {
		if ($this->game->isGameOver()) {
			return 	$this->showSticks() . 
					$this->showWinner() . 
					$this->startOver();
		} else {
			return 	$this->showSticks() . 
					$this->showSelection();
		}
	}


	private function showSticks() : string {
		$numSticks = $this->game->getNumberOfSticks();
		
		$aiDrew = 0;
		$opponentsMove = "";
		if ($this->computerPlayer->hasMoved()) { 
			$aiDrew = $this->computerPlayer->getLastMove()->getNumSticks();
			$opponentsMove = "Your opponent drew $aiDrew stick". ($aiDrew > \model\StickSelection::getMinSelection() ? "s" : ""); //TODO: handle code duplication
		}


		//Make a visualisation of the sticks 
		$sticks = "";
		for ($i = 0; $i < $numSticks; $i++) {
			$sticks .= "I"; //Sticks remaining
		}
		for (; $i < $aiDrew + $numSticks; $i++) {
			$sticks .= "."; //Sticks taken by opponent
		}
		for (; $i < $this->game->getNumberOfSticks(); $i++) {
			$sticks .= "_"; //old sticks
		}

		//TODO: Ewww inline CSS!
		return "<p>$opponentsMove</p>
				<p>There " . ($numSticks > \model\StickSelection::getMinSelection() ? "are" : "is") ." $numSticks stick" . ($numSticks > \model\StickSelection::getMinSelection() ? "s" : "") ." left</p>
				<p style='font-family: \"Courier New\", Courier, monospace'>$sticks</p>
				";
	}

	private function showSelection() : string {
		
		$numSticks = $this->game->getNumberOfSticks();

		$ret = "<h2>Select number of sticks</h2>
				<p>The player who draws the last stick looses. </p>
				<p>$this->errorMessage</p>";
		$ret .= "<ol>";

		//HTML for the selection buttons 
		for ($i = \model\StickSelection::getMinSelection(); 
			 $i <= \model\StickSelection::getMaxSelection() && $i <= $numSticks; 
			 $i++ ) {
			$ret .= "<li>
						<a href='?". self::$DRAW_GET_INDEX ."=$i'>Draw $i stick". ($i > \model\StickSelection::getMinSelection() ? "s" : ""). "</a>
					</li>";
		}
		$ret .= "<ol>";

		return $ret;
	}

	private function showWinner() : string {
		if ($this->game->humanPlayerWon()) {
			return "<h2>Congratulations</h2>
					<p>You forced the opponent to draw the last stick!</p>";
		} else {
			return "<h2>Epic FAIL!</h2>
					<p>You were forced to draw the last stick</p>";
		}
	}

	private function startOver() : string {
		return "<a href='?". self::$STARTOVER_GET_INDEX ."'>Start new game</a>";
	}

	public function getPlayerStartsOver() : bool {
		return isset($_GET[self::$STARTOVER_GET_INDEX]);
	}
}