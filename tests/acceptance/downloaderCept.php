<?php
/** @var \Codeception\TestCase\Cept $this */	//typehint na proměnnou $this
/** @var \Codeception\Scenario $scenario */	//typehint na proměnnou $this

$test = []; //question => correct answer
$test = json_decode(file_get_contents('tests.json'), true);

$I = new AcceptanceTester($scenario);
$I->wantTo('fill test and download results');
$I->amOnPage('/login.php?id=127');
$I->click('input[type="submit"]');
$I->seeInCurrentUrl('test');
$I->see('testu stiskem');

for ($i = 1; $i <= 30; $i++) {
	$question = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
	$answers = [];
//	$answer1 = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"\d+\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
	$answer1number = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"(\d+)\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
	for ($j = 0; $j < 4; $j++) {
		$number = $answer1number + $j;
		$answer = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"$number\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
		$answers[] = $answer;
	}
	$test[$question] = $answers;
}
//$questions = $I->grabTextFrom('form b');
//$questions = $I->grabTextFrom('html');
file_put_contents('tests.json', json_encode($test, JSON_PRETTY_PRINT));
