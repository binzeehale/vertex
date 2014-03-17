<?php
if ( ! function_exists('student_transaction_type2str'))
{
	function student_transaction_type2str($type)
	{

		$data = array(
				'1' => '直接充值',
				'2' => '直接扣款',
				'3' => '奖学金充值',
				'4' => '奖学金扣款',
				'5' => '考勤直接扣款',
				'6' => '考勤奖学金扣款',
				'7' => '班级金额转移'
			);

		return isset($data[$type])?$data[$type]:"";
	}
}