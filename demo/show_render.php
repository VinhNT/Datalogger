<?php
// Set the JSON header
header("Content-type: text/json");
require_once '../classes/util.php';

$deviceId = __getParam('id');

$startDate = __getParam('s');
$endDate = __getParam('e');

// check startDate & endDate
$_startDate = strtotime($startDate);
$_endDate = strtotime($endDate);
if ($_startDate === false) $startDate = '';
if ($_endDate === false) $endDate = '';

if (!empty($deviceId)) {
    session_start();
    $_SESSION['DeviceID'] = $deviceId;

    include_once('../classes/dao.php');
    include_once('../classes/conf.php');

    $dao = new Dao();
    $ret = array();

	// get device information
	$device = $dao->getDeviceByID($deviceId);
	
	$defaultRange = $device->ChartRange;
	$recordInterval = $device->RecordInterval;
	
    $detail = $dao->getDeviceChartDetail($deviceId);
	
    if (count($detail) == 0) {
		// no record found, do nothing
    } else {
        $series = array();
        foreach ($detail as $info) {
            $colName[] = $info['DataCol'];
            $series[$info['DataCol']]['info'] = $info;
            $series[$info['DataCol']]['info']['StartDate'] = '';
        }
		// make date range
		if (!empty($startDate) && !empty($endDate)) {
			$startDate .= ' 00:00:00';
			$endDate .= ' 23:59:59';
			
			$diff = $_endDate - $_startDate;
			$totalDate = floor($diff/86400)+1;
		} else {
			$yesterday = strtotime('now -1 day');
			$endDate = date('Y-m-d', $yesterday).' 23:59:59';

			$previousDate = $yesterday - 86400*$defaultRange*6;
			$startDate = date('Y-m-d', $previousDate).' 00:00:00';
			
			$totalDate = $defaultRange*7;
		}

		// build WHERE string
		// just get MAX_CHART_SAMPLE record from StartDate to EndDate
		$firstRow = $dao->getDeviceData($deviceId, " And RecordDate >= '{$startDate}'", ' Limit 1');
		foreach ($firstRow as $k => $v) {
			$startTime = $k;
			break;
		}
		$samplePerDay = floor(MAX_CHART_SAMPLE / $totalDate);
		$minuteStep = floor(1440 / $recordInterval / $samplePerDay)*60;
		
		$recordDateArr = array();
		$endDate = strtotime($endDate);
		$startDate = strtotime($startTime);
		while ($startDate <= $endDate) {
			$recordDateArr []= date('Y-m-d H:i:s', $startDate);
			$startDate += $minuteStep;
		}
		$where = ' And RecordDate In ("'.join('","', $recordDateArr);
		$where = substr($where, 0, -1);
		$where .= '")';


        $data = $dao->getDeviceData($deviceId, $where);
        foreach ($data as $k => $val) {
            $row = explode(';', $val);
			error_log($val);
            foreach ($row as $col) {
                $col = explode('=', $col);

                if (in_array($col[0], $colName)) {
                    $series[$col[0]]['data'][] = (float)$col[1];

                    if (empty($series[$col[0]]['info']['StartDate'])) {
                        $series[$col[0]]['info']['StartDate'] = strtotime($k); // convert to milisecond
                    }

                    if (empty($series[$col[0]]['info']['yAxis_min'])) {
                        $series[$col[0]]['info']['yAxis_min'] = $col[1];
                    } elseif ($series[$col[0]]['info']['yAxis_min'] > (float)$col[1]) {
                        $series[$col[0]]['info']['yAxis_min'] = ($col[1]);
                    }

                }
            }
        }

        // sort data by Order
        foreach ($series as $col => $arrInfo) {
            $_series[$arrInfo['info']['Order']] = $series[$col];
        }
        
        $ret['total'] = count($colName);
        $ret['data'] = $_series;
		$ret['minuteStep'] = $minuteStep*1000;
        $ret['title'] = 'Plot graph for #'.$deviceId;
    }

} else {
    $ret = array();
}


echo json_encode($ret);

?>