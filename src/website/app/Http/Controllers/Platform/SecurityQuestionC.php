<?php
/*
*这里是密保问题的C文件
*我在考虑这里要不要用那个设计模式
*/
namespace App\Http\Controllers\Platform;
use DB;
use View;
class SecurityQuestionC extends \App\Http\Controllers\Controller
{
	public function platform_securityquestion_show()//这里是显示密保问题的页面
	{
	
		// $info = DB::TABLE('user_profile')->select('problem1', 'problem2', 'problem2', 'answer1', 'answer2', 'answer3')->get();
		$problem = DB::TABLE('problem')->get();

		return View::make('platform.SecurityQuestion')->with('problem', $problem);
	}
	public function platform_securityquestion_addaction()//这个方法是ADD密保问题
	{
		$a1 = $_POST['answer1'];
		$a2 = $_POST['answer2'];
		$a3 = $_POST['answer3'];
		$p1 = $_POST['problem1'];
		$p2 = $_POST['problem2'];
		$p3 = $_POST['problem3'];
		
		if ($p1 != $p2 || $p2 != $p3 || $p1 != $p3 || trim($a1) != '0' && trim($a2) != '0' && trim($a3) != '0' )//参数过滤，在考虑放在哪里。是过滤器中还是C中
		{
			$exits = DB::SELECT('SELECT id FROM problem where id in (?, ? ,?)', array($p1, $p2, $p3));
			if (count($exits, 0) == 3)//判断传递过来的值是否在DB中有记录
			{
				//@ini_set('session.gc_maxlifetime', GetInfo::SESSION_TIME());
				//@session_start();
				
				$rst = DB::UPDATE("UPDATE `user_info` SET `problem_first` = ?, `problem_second` = ?, `problem_third` = ?, `answer_first` = ?, `answer_second` = ?, `answer_third` = ? where user_id = ?", array($p1, $p2, $p3, $a1, $a2, $a3, $_SESSION['user']['user']['id']));
				
				if ($rst)
				{
					return '1';
				}
				else
				{
					return '0';
				}
				
			}
			else
			{
			// header ('Content-type: text/html; charset=utf-8');
			// echo '<script>alert("请求的参数中带有不正确的参数，该请求已放弃。");window.location.href="/user/securityquestion"</script>';	
			// die();		
				return 0;
			}			
		}
		else
		{
			// header ('Content-type: text/html; charset=utf-8');
			// echo '<script>alert("请求的参数中带有不正确的参数，该请求已放弃。");window.location.href="/user/securityquestion"</script>';	
			// die();
			return 0;
		}
	}

}