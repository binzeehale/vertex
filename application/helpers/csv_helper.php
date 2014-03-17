<?php
if ( ! function_exists('csv_from_array'))
{
	function csv_from_array($array, $header , $delim = ",", $newline = "\n", $enclosure = '"')
	{

		$out = '';

		// First generate the headings from the table column names
		foreach ($header as $name)
		{
			$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure, $name).$enclosure.$delim;
		}

		$out = rtrim($out);
		$out .= $newline;

		// Next blast through the result array and build out the rows
		foreach ($array as $row)
		{
			foreach ($row as $item)
			{
				$out .= $enclosure.str_replace($enclosure, $enclosure.$enclosure,  iconv("UTF-8","GB2312//IGNORE",$item)).$enclosure.$delim;
			}
			$out = rtrim($out);
			$out .= $newline;
		}

		return $out;
	}
}