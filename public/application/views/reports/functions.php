<?php
function d($date) {
	if (empty($date)) return null;
	
	$months = array(
		1 => 'январь',
		'февраль',
		'март',
		'апрель',
		'май',
		'июнь',
		'июль',
		'август',
		'сентябрь',
		'октябрь',
		'ноябрь',
		'декабрь',
	);
	
	$darr = explode('-', $date);
	
	return $darr[2] . ' ' . $months[(int)$darr[1]] . ' ' . $darr[0] . ' й';
}