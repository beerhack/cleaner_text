<?php
###############################################################
#
#	Cleaner text by beerhack from http://beerhack.name
#	ICQ: 274717
#
###############################################################

ini_set('memory_limit', '512M'); //чем больше текстовый файл, тем больше памяти понадобится
$filename = 'text.txt'; //имя текстового файла, файл должен быть в папке со скриптом, кодировка текста UTF-8
$lang = 'ru'; //допустимые буквы: ru - русские; en - английские ; ruen - русские и английские
$sym = '.-!,?'; //допустимые символы

/* настройки выше, ниже ничего не трогать */

$f = fopen($filename, 'r');
$contents = fread($f, filesize($filename));
fclose($f);
for($i=0;$i<strlen($sym);$i++){
	$sympattern .= '\\'.$sym[$i];
	if($i<strlen($sym)-1) {
		$orsympattern .= '\\'.$sym[$i].'|';
	} else {
		$orsympattern .= '\\'.$sym[$i];
	}
}
if($lang=='en'){
	$pattern = "/[^a-zA-Z0-9 $sympattern]+/u";
}elseif($lang=='ru'){
	$pattern = "/[^а-яА-Я0-9 $sympattern]+/u";
}elseif($lang=='ruen'){
	$pattern = "/[^a-zA-Zа-яА-Я0-9 $sympattern]+/u";
}
$contents = preg_replace(array('/[\t]/u','/[\r]/u','/[\n]/u','/[\v]/u'), ' ', $contents); //заменяем символы табуляции, переноса строки на пробел
$contents = preg_replace($pattern, '', $contents); //удаляем все лишние символы
$contents = preg_replace("/($orsympattern)[\s$orsympattern]*($orsympattern)/u", "\$1", $contents); // удаляем идущие подряд допустимые символы
$contents = preg_replace('/[\s]*(\.|\,)/u', "\$1", $contents); //удаляем пробелы перед знаками препинания
$contents = preg_replace('/[ ]{2,}/u', ' ', $contents); //удаляем повторные пробелы
$f = fopen('clean-'.$filename, 'w'); //итоговый файл будет с приставкой 'clean-'
fwrite($f, $contents);
fclose($f);
?>