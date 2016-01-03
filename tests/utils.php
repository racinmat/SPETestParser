<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 2. 1. 2016
 * Time: 22:27
 */


require_once __DIR__ . '/classes.php';

/**
 * @param string $filename
 * @return Question[]
 */
function loadFromJson($filename) {
	/** @var Question[] $allTests */
	$allTests = json_decode(file_get_contents($filename), true);
	foreach ($allTests as $answer1number => $question) {
		$answers = [];
		foreach ($question['answers'] as $answer) {
			$answers[] = new Answer($answer['text'], $answer['correct'], $answer['id'], $answer['tried']);
		}
		$questionObject = new Question($question['text'], $answers);
		$questionObject->selected = null;
		if ($question['correctAnswer'] == null) {
			$questionObject->correctAnswer = null;
		} else {
			$questionObject->correctAnswer = new Answer($question['correctAnswer']['text'], $question['correctAnswer']['correct'], $question['correctAnswer']['id'], $question['correctAnswer']['tried']);
		}
		$allTests[$answer1number] = $questionObject;
	}
	return $allTests;
}