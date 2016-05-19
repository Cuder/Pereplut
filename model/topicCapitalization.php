<?php
$lowercaseExceptions = array(
// Prepositions
	'aboard', 'about', 'above', 'abreast', 'abroad', 'across', 'after', 'against', 'along', 'alongside', 'amid', 'amidst', 'among', 'apropos', 'around', 'as', 'astride', 'at', 'atop',
	'before', 'behind', 'below', 'beneath', 'beside', 'besides', 'between', 'beyond', 'by',
	'c', 'ca', 'circa',
	'despite', 'down', 'during',
	'except',
	'for', 'from',
	'in', 'inside', 'into',
	'less', 'like',
	'near', 'nearer', 'nearest', 'notwithstanding',
	'of', 'off', 'on', 'onto', 'ontop', 'out', 'outside', 'over',
	'per', 'pre', 'pro',
	'qua', 're',
	'sans', 'since',
	'than', 'through', 'thru', 'to', 'toward', 'towards',
	'under', 'underneath', 'unlike', 'until', 'up', 'upon', 'upside',
	'versus', 'via', 'vice', 'vs',
	'with', 'within', 'without',
// Conjunctions (excluding homonymical prepositions)
	'although', 'and',
	'because', 'but',
	'either',
	'else',
	'how',
	'if', 'inasmuch',
	'lest',
	'neither', 'nor',
	'once', 'or',
	'so',
	'that', 'though', 'till',
	'unless',
	'what', 'whatever', 'when', 'whenever', 'where', 'wherever', 'whereas', 'whether', 'which', 'whichever', 'while', 'who', 'whoever', 'whom', 'whomever', 'whose', 'why',
	'yet',
// Articles
	'a',
	'an',
	'the',
// Other exceptions
	'da', 'de', 'den', 'der',
	'und',
	'van', 'von'
);

// Getting project file path
$projectPath = $projectDir."/project.hmxp";
if (!file_exists($projectPath)) {
	foreach (glob($projectDir."/*.hmxp") as $projectPath) { break; }
}

// Getting list of Table of Contents
$tocs = getTOCs($projectPath, $projectDir);

foreach ($tocs as $toc) {
	$dom = createDom($toc["path"]);

	$captions = $dom->getElementsByTagName("caption");
	$changedToc = false;

	foreach ($captions as $caption) {
		$topicTitle = $caption->nodeValue;
		if (preg_match('/[а-яА-Я]/', $topicTitle) == 0) {
			$words = explode(' ', $topicTitle);
			foreach ($words as $key => $word) {
				if (
					!starts_with_upper($word) &&
					(
						$key == 0 or
						!array_key_exists($key+1, $words) or
						(
							$key != 0 &&
							array_key_exists($key+1, $words) &&
							!in_array($word, $lowercaseExceptions)
						)
					)
				) {
					$words[$key] = ucwords($word);
				}
			}
			$newTopicTitle = implode(' ', $words);
			if ($topicTitle != $newTopicTitle) {
				$caption->nodeValue = htmlspecialchars($newTopicTitle, ENT_XML1);
				$changedToc = true;
			}
		}
	}

	if ($changedToc == true) {
		$xml_string = $dom->saveXML();
		$fp = fopen($toc["path"], 'w');
		fwrite($fp, $xml_string);
	}
}

function starts_with_upper($str) {
	$chr = mb_substr($str, 0, 1, "UTF-8");
	return mb_strtolower($chr, "UTF-8") != $chr;
}