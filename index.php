<?php
// core function
function w_check($word,$letters) {
	$cnt = 0;
	do {
//		echo $word." ".$letters." ".$cnt."<br>";
		$i=0;
		// find common char in both words
		while ($i<strlen($word) && ($pos = stripos($letters,$word[$i]))===false) {
			 $i++;
		}
		if ($pos !== false) {
			//remove the char from words and process again
			$word = substr_replace($word, '', $i, 1);
			$letters = substr_replace($letters, '', $pos, 1);
			$cnt++;
		}
		else {
			$cnt = 0;
			break;
		}
	}
	while (!empty($word));
	return $cnt;
}

function parse_words($fname) {
    $file = fopen($fname, 'r');
    while ($line = fgets($file)) {
		$dict[] = str_replace("\r\n", '', $line);
	}
	return $dict;
}


if ($_POST) {
	echo 'Matching words for letters: "'.$_POST['letters'].'"<br>';
	// enter dictionary and letters
	if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
		$dict = parse_words($_FILES['userfile']['tmp_name']);

		$letters = $_POST['letters'];
		// find and print matching words
		$cnt=0;
		foreach ($dict as $word)
			if (w_check($word,$letters)>=3) {
				echo $word."<br>";
				$cnt++;
			}
		echo "<br>$cnt words total.";
	}
}
	
?>
<form method="post" enctype="multipart/form-data">
<p>
Choose dictionary file:<input type="file" name="userfile"/><br><br>
Enter letters:<input type="text" name="letters"/>
<input type="submit" value="Submit"/>
</p>
</form>