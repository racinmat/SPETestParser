<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 3. 1. 2016
 * Time: 11:35
 */

require_once 'utils.php';

/** @var Question[] $allTests */
$allTests = loadFromJson(__DIR__ . '/../tests.json');

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