<?php

namespace model;

/**
 * Encapsulate how many sticks a player draws
 * can be 1,2,3 or 4
 */
class StickSelection
{

	const MIN_SELECTION = 1;
	const MAX_SELECTION = 6;

	/**
	 * @var int (1,2,3,4)
	 */
	private $amount;

	public function getNumSticks(): int
	{
		return $this->amount;
	}

	/**
	 * Private constructor makes sure we cannot create outside of 1,2,3,4
	 */
	public function __construct(int $amount)
	{
		$this->amount = $amount;
	}

	public static function getMinSelection(): int
	{
		return self::MIN_SELECTION;
	}

	public static function getMaxSelection(): int
	{
		return self::MAX_SELECTION;
	}
}
