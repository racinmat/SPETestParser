<?php

require_once __DIR__ . '/tests/utils.php';

if ($_GET['sorted']) {
	/** @var Question[] $allTests */
	$allTests = loadFromJson(__DIR__ . '/testsSorted.json');
} else {
	/** @var Question[] $allTests */
	$allTests = loadFromJson(__DIR__ . '/tests.json');
}
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
