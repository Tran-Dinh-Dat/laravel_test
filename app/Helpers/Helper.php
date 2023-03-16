<?php

if (!function_exists('convertInchToPixel')) {
	function convertInchToPixel($inch)
	{
		return round($inch * 96, 2);
	}
}
