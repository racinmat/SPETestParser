<?php

require_once __DIR__ . '/../classes.php';

class downloaderCest
{

	/** @var Question[] $allTests */
	protected $allTests;

	protected $currentTest;

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

	public function _failed(AcceptanceTester $I)
	{
		file_put_contents('failedTests.json', json_encode($this->allTests, JSON_PRETTY_PRINT));
		file_put_contents('failedTestsCurrent.json', json_encode($this->currentTest, JSON_PRETTY_PRINT));
	}

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
		$this->allTests = json_decode(file_get_contents('tests.json'), true);
		foreach ($this->allTests as $answer1number => $question) {
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
			$this->allTests[$answer1number] = $questionObject;
		}
		$this->currentTest = []; //idčka první odpovědi k otázkám v testu

		$I->wantTo('fill test and download results');
		$I->amOnPage('/login.php?id=127');
		$I->click('input[type="submit"]');
		$I->seeInCurrentUrl('test');
		$I->see('testu stiskem');

		for ($i = 1; $i <= 30; $i++) {
			$questionText = $I->grabTextFrom("~$i.<\/b> otázka \(.*?\) - +<b>(.*?)<\/b>~");
			$answer1number = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"(\d+)\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
			$this->currentTest[] = $answer1number;
			if (array_key_exists($answer1number, $this->allTests)) {

			} else {
				/** @var Answer[] $answers */
				$answers = [];
				$answersCount = $I->getNumberOfElements("input[name=a$i]");
				for ($j = 0; $j < $answersCount; $j++) {
					$number = $answer1number + $j;
					$answerText = $I->grabTextFrom("~<TD VALIGN=\"MIDDLE\"><INPUT TYPE=\"radio\" VALUE=\"$number\" NAME=\"a$i\" \/><\/TD><TD>(.*?)<BR /></TD>~i");
					$answers[] = new Answer($answerText, null, $number, false);
				}
				$this->allTests[$answer1number] = new Question($questionText, $answers);
			}
		}

		//naklikání nevyzkoušených odpovědí
		foreach ($this->currentTest as $answer1number) {
			/** @var Question $question */
			$question = $this->allTests[$answer1number];
			$I->wantTo('select correct answer of ' . json_encode($question));
			if ($question->hasCorrectAnswer()) {
				$id = $question->correctAnswer->id;
				$I->click("input[value=\"$id\"]");
				continue;
			}
			foreach ($question->answers as $answer) {
				if (!$answer->tried) {
					//první nevyzkoušená otázka
					$id = $answer->id;
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
		foreach ($this->currentTest as $answer1number) {
			/** @var Question $question */
			$question = $this->allTests[$answer1number];
			$i++;   //číslo otázky
			if (!$question->hasCorrectAnswer()) {
				$image = $I->grabTextFrom("~<img alt=\"status\" src=\"(.*?)\"(.*?)</td><td><b>$i\.~i");
				if ($image == 'img/icon_good.jpg') {
					$question->selected->correct = true;
					$question->correctAnswer = $question->selected;
				}
			}
		}

		foreach ($this->allTests as $questionText => $question) {
			$question->selected = null;
		}

		file_put_contents('tests.json', json_encode($this->allTests, JSON_PRETTY_PRINT));

    }
}
