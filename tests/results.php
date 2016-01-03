<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 3. 1. 2016
 * Time: 11:35
 */

require_once 'classes.php';

/** @var Question[] $allTests */
$allTests = json_decode(file_get_contents(__DIR__ . '/../tests.json'), true);
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

$total = 0;
$correct = 0;
foreach ($allTests as $answer1number => $question) {
	$total++;
	if ($question->hasCorrectAnswer()) {
		$correct++;
	}
}

echo "Celkem otázek: $total.\n";
echo "Z toho má správné odpovědi: $correct.\n";

$learningSet = [];
foreach ($allTests as $answer1number => $question) {
	$learningSet[$answer1number] = [$question->text, $question->correctAnswer->text];
}

$learningSetText = "";
foreach ($learningSet as $questionAndAnswer) {
	list($question, $answer) = $questionAndAnswer;
	$learningSetText .= "$question\n\t$answer.\n";
}
file_put_contents('otazky.txt', $learningSetText);