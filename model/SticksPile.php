<?php

namespace model;


class SticksPile
{
	private const STARTING_NUMBER_OF_STICKS = 32;
	private const GAME_OVER_THRESHOLD = 2;

	private $sticksLeft;

	public function __construct()
	{
		$this->newGame();
	}

	public function newGame()
	{
		$this->sticksLeft = self::STARTING_NUMBER_OF_STICKS;
	}

	/**
	 * Its game over if its only 1 stick left
	 */
	public function isGameOver(): bool
	{
		return $this->sticksLeft < self::GAME_OVER_THRESHOLD;
	}

	/**
	 * @return int 
	 */
	public function getNumberOfSticks(): int
	{
		return $this->sticksLeft;
	}

	public function getGameOverThreshold(): int
	{
		return self::GAME_OVER_THRESHOLD;
	}

	/**
	 * We can only remove 1-3 sticks 
	 * Cannot remove more than we have
	 */
	public function removeSticks(StickSelection $selection)
	{

		if ($selection->getNumSticks() > $this->sticksLeft)
			throw new \Exception("Not allow to draw more than the pile contains");

		$this->sticksLeft -= $selection->getNumSticks();
	}
}
