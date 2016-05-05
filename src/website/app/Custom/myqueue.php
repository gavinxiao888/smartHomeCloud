<?php
class myqueue 
{

	public function session($job , $data)
	{
		$job->delete();
		session_start();
		$_SESSION["queue"]=__LINE__;				

	}
	public function queue($job,$data)
	{
		// session_start();
		$_SESSION["queue"]=__LINE__;		
		
		$job->delete();
	}

}

?>