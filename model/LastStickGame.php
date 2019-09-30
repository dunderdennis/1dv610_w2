<?php

namespace model;

require_once("model/StickSelection.php");

class LastStickGame
{
	private $isPlayerOne;
	private $pile;

	public function __construct(SticksPile $pile, Session $session)
	{
		$this->pile = $pile;
		$this->session = $session;
		$this->startNewGame();
	}

	public function isGameOver(): bool
	{
		return $this->pile->getNumberOfSticks() < $this->pile->getGameOverThreshold(); // HÄR VAR DET ETT MAGISKT NUMMER
	}

	public function humanPlayerWon(): bool
	{
		return $this->isGameOver() && $this->isPlayerOne == false;
	}

	//Facade pattern to simplify INTRESSANT
	public function getNumberOfSticks(): int
	{
		return $this->pile->getNumberOfSticks();
	}

	public function getMinSelection(): int
	{
		return $this->pile->getNumberOfSticks();
	}

	public function getMaxSelection(): int
	{
		return $this->pile->getNumberOfSticks();
	}


	public function startNewGame()
	{
		$this->pile->newGame();
		$this->isPlayerOne = true;
	}

	/*
	* This method makes sure the correct player makes the move and that is important to know who won.
	*/
	public function doMove(StickSelection $selection, bool $isPlayerOne)
	{
		if ($this->isPlayerOne != $isPlayerOne)
			throw new \Exception("Wrong player move");

		$this->isPlayerOne = !$this->isPlayerOne; //swap players

		$this->pile->removeSticks($selection);

		$this->session->setSession($this);
	}
}
