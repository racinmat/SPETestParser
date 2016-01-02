<?php
/** @var \Codeception\TestCase\Cept $this */	//typehint na proměnnou $this
/** @var \Codeception\Scenario $scenario */	//typehint na proměnnou $this

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
	 */
	public function __construct($text)
	{
		$this->correctAnswer = null;
		$this->answers = [];
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

/** @var Question[] $allTests */
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
	$questionText = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
	$currentTest[] = $questionText;
	if (array_key_exists($questionText, $allTests)) {
		$question = $allTests[$questionText];
	} else {
		/** @var Answer[] $answers */
		$answers = [];
		$answer1number = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"(\d+)\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
		$answersCount = $I->getNumberOfElements("input[name=a$i]");
		for ($j = 0; $j < $answersCount; $j++) {
			$number = $answer1number + $j;
			$answerText = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"$number\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
			$answers[] = new Answer($answerText, null, $number, false);
		}
		$allTests[$questionText] = new Question($questionText);
		$allTests[$questionText]->answers = $answers;
	}
}

//naklikání nevyzkoušených odpovědí
$i = 0;
foreach ($currentTest as $questionText) {
	/** @var Question $question */
	$question = $allTests[$questionText];
	$i++;   //číslo otázky
	if ($question->hasCorrectAnswer()) {
		$id = $question->correctAnswer->id;
		$I->click("input[value=\"$id\"]");
		continue;
	}
	foreach ($question->answers as $answer) {
		if (!$answer->tried) {
			//první nevyzkoušená otázka
			$id = $answer->id;
//			$I->selectOption("input[name=a$i]", $id);
			$I->click("input[value=\"$id\"]");
			$question->selected = $answer;
			$answer->tried = true;
			break;
		}
	}
}

//submit formuláře
$I->click('input[type=button]');

//přečtení správnosti
$i = 0;
foreach ($currentTest as $questionText) {
	/** @var Question $question */
	$question = $allTests[$questionText];
	$i++;   //číslo otázky
	$image = $I->grabTextFrom("~<img alt=\"status\" src=\"(.*?)\"(.*?)</td><td><b>$i\.~i");
	if ($image == 'img/icon_good.jpg') {
		$question->selected->correct = true;
		$question->correctAnswer = $question->selected;
	}
}

foreach ($allTests as $questionText => $question) {
	$question->selected = null;
}


file_put_contents('tests.json', json_encode($allTests, JSON_PRETTY_PRINT));
file_put_contents('testing.json', json_encode($array, JSON_PRETTY_PRINT));
