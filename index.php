<?php

require_once __DIR__ . '/tests/utils.php';

/** @var Question[] $allTests */
$allTests = loadFromJson(__DIR__ . '/tests.json');
?>

<html>
<head>
	<meta charset="UTF-8">
</head>
<?php
foreach ($allTests as $question) {
	echo '<hr>';
	echo $question->text;
	echo '<ul>';
	foreach ($question->answers as $answer) {
		echo '<li>';
		echo $answer->correct ? '<b>' : null;
		echo $answer->text;
		echo $answer->correct ?'</b>' : null;
		echo '</li>';
	}
	echo '</ul>';

}
?>

</html>
