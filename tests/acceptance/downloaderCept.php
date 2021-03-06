<?php
///** @var \Codeception\TestCase\Cept $this */	//typehint na proměnnou $this
///** @var \Codeception\Scenario $scenario */	//typehint na proměnnou $this
//
//require_once __DIR__ . '/../classes.php';
//
///** @var Question[] $allTests */
//$allTests = json_decode(file_get_contents('tests.json'), true);
//foreach ($allTests as $questionText => $question) {
//	$answers = [];
//	foreach ($question['answers'] as $answer) {
//		$answers[] = new Answer($answer['text'], $answer['correct'], $answer['id'], $answer['tried']);
//	}
//	$questionObject = new Question($question['text'], $answers);
//	$questionObject->selected = null;
//	if ($question['correctAnswer'] == null) {
//		$questionObject->correctAnswer = null;
//	} else {
//		$questionObject->correctAnswer = new Answer($question['correctAnswer']['text'], $question['correctAnswer']['correct'], $question['correctAnswer']['id'], $question['correctAnswer']['tried']);
//	}
//	$allTests[$questionText] = $questionObject;
//}
//$currentTest = []; //question => correct answer
//
//$I = new AcceptanceTester($scenario);
//$I->wantTo('fill test and download results');
//$I->amOnPage('/login.php?id=127');
//$I->click('input[type="submit"]');
//$I->seeInCurrentUrl('test');
//$I->see('testu stiskem');
//
//$array = [];
//for ($i = 1; $i <= 30; $i++) {
//	$questionText = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
//	$currentTest[] = $questionText;
//	if (array_key_exists($questionText, $allTests)) {
//		$question = $allTests[$questionText];
//	} else {
//		/** @var Answer[] $answers */
//		$answers = [];
//		$answer1number = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"(\d+)\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
//		$answersCount = $I->getNumberOfElements("input[name=a$i]");
//		for ($j = 0; $j < $answersCount; $j++) {
//			$number = $answer1number + $j;
//			$answerText = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"$number\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
//			$answers[] = new Answer($answerText, null, $number, false);
//		}
//		$allTests[$questionText] = new Question($questionText, $answers);
//	}
//}
//
////naklikání nevyzkoušených odpovědí
//foreach ($currentTest as $questionText) {
//	/** @var Question $question */
//	$question = $allTests[$questionText];
//	if ($question->hasCorrectAnswer()) {
//		$I->wantTo('select correct answer of ' . json_encode($question));
//		$id = $question->correctAnswer->id;
//		$I->click("input[value=\"$id\"]");
//		continue;
//	}
//	foreach ($question->answers as $answer) {
//		if (!$answer->tried) {
//			//první nevyzkoušená otázka
//			$id = $answer->id;
//			$I->click("input[value=\"$id\"]");
//			$question->selected = $answer;
//			$answer->tried = true;
//			break;
//		}
//	}
//}
//
////submit formuláře
//$I->click('input[type=button]');
//
////přečtení správnosti
//$i = 0;
//foreach ($currentTest as $questionText) {
//	/** @var Question $question */
//	$question = $allTests[$questionText];
//	$i++;   //číslo otázky
//	$image = $I->grabTextFrom("~<img alt=\"status\" src=\"(.*?)\"(.*?)</td><td><b>$i\.~i");
//	if ($image == 'img/icon_good.jpg') {
//		$question->selected->correct = true;
//		$question->correctAnswer = $question->selected;
//	}
//}
//
//foreach ($allTests as $questionText => $question) {
//	$question->selected = null;
//}
//
//
//file_put_contents('tests.json', json_encode($allTests, JSON_PRETTY_PRINT));
//file_put_contents('testing.json', json_encode($array, JSON_PRETTY_PRINT));
