<?php
/**
 * php version >= 5.4
 */
class KMP
{
	// private $next = [];

	public static function search($s, $t, $pos = 0)
	{
		$next = self::getNext($t);
		$i = $pos - 1;
		$j = -1;
		$slen = strlen($s);
		$tlen = strlen($t);
		while ($i < $slen && $j < $tlen) {
			if ($j == -1 || $s[$i] == $t[$j]) {
				++$i;
				++$j;
			} else {
				$j = $next[$j];
			}
		}
		if ($j >= $tlen) {
			return $i - $tlen;
		}
		return false;
	}

	public static function getNext($t)
	{
		$i = 0;
		$j = -1;
		$next = [-1];
		$tlen = strlen($t);
		while ($i < $tlen) {
			if ( $j == -1 || $t[$i] == $t[$j] ) {
				++$i;
				++$j;
				$next[$i] = $j;
			} else {
				$j = $next[$j];
			}
		}
		return $next;	
	}
}
