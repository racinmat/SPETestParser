<?php
/** @var \Codeception\TestCase\Cept $this */	//typehint na proměnnou $this
/** @var \Codeception\Scenario $scenario */	//typehint na proměnnou $this

$test = []; //question => correct answer

$I = new AcceptanceTester($scenario);
$I->wantTo('fill test and download results');
$I->amOnPage('/login.php?id=127');
$I->click('input[type="submit"]');
$I->seeInCurrentUrl('test');
$I->see('testu stiskem');

for ($i = 1; $i <= 30; $i++) {
	$question = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
	$answers = $I->grabTextFrom("~<TR><TD>(.*?)<\/TD><TD VALIGN=\"MIDDLE\"><INPUT(.*?)NAME=\"a$i\"(.*?)><\/TD><TD>(.*?)<\/TD><\/TR>~/i");
	$test[$question] = $answers;
}
//$questions = $I->grabTextFrom('form b');
//$questions = $I->grabTextFrom('html');
file_put_contents('tests.json', json_encode($test, JSON_PRETTY_PRINT));
