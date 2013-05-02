<?php
	set_time_limit(-1);
	ini_set('max_execution_time', 3000);
	
	require_once 'classes/Membership.php';
	require_once 'classes/conf.php';
	require_once 'classes/dao.php';
	$membership = new Membership();
	$membership->confirm_Member();


	
	$totalItems = 100000;
	$step=$totalItems/10;
	$trans = array($step, $step*2, $step*3, $step*4, $step*5, $step*6, $step*7, $step*8, $step*9);
	$start = time()-$totalItems*60;
	
	$dao = new Dao();	
	mysql_query("SET AUTOCOMMIT=0");
	$dao->beginTransaction();

	$dao->__truncateTable('tbldevicedata');
	for ($i=0; $i<$totalItems; $i++) {
		$data = array(
			'CR800',
			date('Y-m-d H:i:s', $start),
			date('Y-m-d H:i:s', $start),
			$i,
			'Temp='.rand(0, 30).';Temp2='.rand(10, 35).';Pin='.rand(10,20)
		);
		
		$dao->__insertChartData($data);
		$start += 60;
		
		if (in_array($i, $trans)) {
			$dao->commitTransaction();
			$dao->beginTransaction();
		}
	}
	$dao->commitTransaction();

	echo 'xong';
?>