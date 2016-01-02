<?php
/** @var \Codeception\TestCase\Cept $this */	//typehint na proměnnou $this
/** @var \Codeception\Scenario $scenario */	//typehint na proměnnou $this


$allTests = json_decode(file_get_contents('tests.json'), true);
$currentTest = []; //question => correct answer

$I = new AcceptanceTester($scenario);
$I->wantTo('fill test and download results');
$I->amOnPage('/login.php?id=127');
$I->click('input[type="submit"]');
$I->seeInCurrentUrl('test');
$I->see('testu stiskem');

$array = [];
for ($i = 1; $i <= 30; $i++) {
	$question = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
	$currentTest[] = $question;
	if (array_key_exists($question, $allTests)) {
		$answers = $allTests[$question];
	} else {
		$answers = [];
		$answer1number = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"(\d+)\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
		$answersCount = $I->getNumberOfElements("input[name=a$i]");
		for ($j = 0; $j < $answersCount; $j++) {
			$number = $answer1number + $j;
			$answerText = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"$number\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
			$answers[] = ['text' => $answerText, 'tried' => false, 'correct' => null, 'id' => $number, 'selected' => false];
		}
		$allTests[$question] = ['answers' => $answers, 'hasCorrectAnswer' => false];
	}
}

//naklikání nevyzkoušených odpovědí
$i = 0;
foreach ($currentTest as $question) {
	$answers = $allTests[$question];
	$i++;   //číslo otázky
	if ($answers['hasCorrectAnswer']) {
		continue;
	}
	foreach ($answers['answers'] as $answer) {
		if (!$answer['tried']) {
			$I->wantTo("click answer" . json_encode($answer));
			//první nevyzkoušená otázka
			$id = $answer['id'];
//			$I->selectOption("input[name=a$i]", $id);
			$I->click("input[value=\"$id\"]");
			$answer['selected'] = true;
			$answer['tried'] = true;
			break;
		}
	}
}

//submit formuláře
$I->click('input[type=button]');



file_put_contents('tests.json', json_encode($allTests, JSON_PRETTY_PRINT));
file_put_contents('testing.json', json_encode($array, JSON_PRETTY_PRINT));

$I->pauseExecution();
