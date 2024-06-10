<?php
namespace Kapps\Model\Utils;

/**
 * summary
 */
class Colors
{
	public static function get_random_color()
	{
		$color_array = array(
			'#a4c400', // Lime
			'#60a917', // Green
			'#008a00', // Emerald
			'#00aba9', // Teal
			'#1ba1e2', // Cyan
			'#0050ef', // Cobalt
			'#6a00ff', // Indigo
			'#aa00ff', // Violet
			'#f472d0', // Pink
			'#d80073', // Magenta
			'#a20025', // Crimson
			'#e51400', // Red
			'#fa6800', // Orange
			'#f0a30a', // Amber
			'#e3c800', // Yellow
			'#825a2c', // Brown
			'#6d8764', // Olive
			'#647687', // Steel
			'#76608a', // Mauve
			'#a0522d', // Sienna
		);

		$k = array_rand($color_array);
		$v = $color_array[$k];
		return $v;
	}

}