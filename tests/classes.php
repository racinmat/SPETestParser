<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 2. 1. 2016
 * Time: 21:31
 */

class Question {
	/** @var Answer[] */
	public $answers;
	/** @var Answer */
	public $correctAnswer;
	/** @var Answer */
	public $selected;
	/** @var string */
	public $text;

	/**
	 * Question constructor.
	 * @param string $text
	 * @param Answer[] $answers
	 */
	public function __construct($text, $answers)
	{
		$this->correctAnswer = null;
		$this->answers = $answers;
		$this->text = $text;
		$this->selected = null;
	}

	public function hasCorrectAnswer()
	{
		return $this->correctAnswer != null;
	}
}

class Answer {
	/** @var string */
	public $text;
	/** @var bool */
	public $correct;
	/** @var int */
	public $id;
	/** @var bool */
	public $tried;

	/**
	 * Answer constructor.
	 * @param string $text
	 * @param bool $correct
	 * @param int $id
	 * @param bool $tried
	 */
	public function __construct($text, $correct, $id, $tried)
	{
		$this->text = $text;
		$this->correct = $correct;
		$this->id = $id;
		$this->tried = $tried;
	}

}
