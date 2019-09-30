<?php

namespace model;

class ComputerPlayer
{

	//TODO: Calculate these instead
	//TODO: These values are dependent on LastStickGame::STARTING_NUMBER_OF_STICKS
	//TODO: These values are also dependent on \model\StickSelection::MIN_SELECTION and StickSelection::MAX_SELECTION
	private $desiredAmountAfterDraw; // = array(21, 17, 13, 9, 5, 1);

	private $gameState;
	private $lastMove;


	public function __construct(LastStickGame $state)
	{
		$this->gameState = $state;
		$this->desiredAmountAfterDraw = $this->calculateDesiredAmountAfterDraw();
	}

	private function calculateDesiredAmountAfterDraw(): array
	{
		$minSelection = \model\StickSelection::MIN_SELECTION;
		$maxSelection = \model\StickSelection::MAX_SELECTION;
		$startingNumberOfSticks = $this->gameState->getNumberOfSticks();
		$numbersArray = [];

		for ($i = $startingNumberOfSticks; $i > $minSelection; $i -= $maxSelection) {
			array_push($numbersArray, $i - 1);
		}
		// var_dump($numbersArray);
		return $numbersArray;
	}

	public function getSelection(): StickSelection
	{
		// TA ARRAYEN I BEAKTNING HÄR NERE
		$amountOfSticksLeft = $this->gameState->getNumberOfSticks();

		$numberOfSticksToDraw = 0;

		$maxSelection = \model\StickSelection::MAX_SELECTION;
		$minSelection = \model\StickSelection::MIN_SELECTION;

		foreach ($this->desiredAmountAfterDraw as $desiredSticks) {
			if ($amountOfSticksLeft > $desiredSticks) {
				$difference = $amountOfSticksLeft - $desiredSticks; // HMM PÅ DENNA RAD
				var_dump($difference);

				if ($difference > $maxSelection || $difference < $minSelection) {
					//AI Player can still loose
					$numberOfSticksToDraw = (rand() % $maxSelection) + $minSelection;
				} else {
					//AI Player has already won, 
					echo 'nu går jag in här';
					$numberOfSticksToDraw = $difference;
				}
				break;
			}
		}

		$this->lastMove = new StickSelection($numberOfSticksToDraw);

		return $this->lastMove;
	}


	public function hasMoved(): bool
	{
		return $this->lastMove != null;
	}


	public function getLastMove(): StickSelection
	{
		if ($this->hasMoved() == false)
			throw new \Exception("Has not made move yet");
		return $this->lastMove;
	}
}
