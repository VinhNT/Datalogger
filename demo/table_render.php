<?php
// Set the JSON header
header("Content-type: text/json");

$deviceId = @$_REQUEST['id'];
if (!empty($deviceId)) :
    session_start();
    $_SESSION['DeviceID'] = $deviceId;
    
    $max_record = 100;
    
    include_once('../classes/conf.php');
	include_once('../classes/dao.php');
    include_once('../classes/util.php');

	$dao = new Dao();
	
	$totalRec = $dao->countDeviceData($deviceId);	
	if ($totalRec > $max_record) $limit = ' Limit '.($totalRec-$max_record-1).','.($totalRec-1);
	else $limit = ' Limit 0,'.($totalRec-1);
	
	$data = $dao->getDeviceFullInfo($deviceId, '', '', $limit);
    
    $deviceDetail = $dao->getDeviceChartDetail($deviceId);

    ob_start();
?>

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="listing">
	<tr>
        <th>#</th>
        <th>RecordDate</th>
        <th>RecordNo</th>
        <?php foreach ($deviceDetail as $dt): ?>
        <th><?= $dt['DataCol']; ?></th>
        <?php endforeach; ?>
    </tr>
    <?php $i = 1; ?>
    <?php foreach ($data as $row): ?>
    <tr>
        <td align="center"><?= $i++; ?></td>
        <td align="center"><?= $row['RecordDate']; ?></td>
        <td align="center"><?= $row['LoggerRecordNo']; ?></td>
        <?php 
        $_data = explode(';', $row['Data']); 
        $_record = array();
    	foreach ($_data as $val) {
            $_val = explode('=', $val); 
            $_record[$_val[0]] = $_val[1];
        }
        ?>
        <?php foreach ($deviceDetail as $dt): ?>
        <td align="center"><?= $_record[$dt['DataCol']]; ?></td>
        <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
</table>

<?php
    $html = ob_get_contents();
    ob_end_clean();

    // cleanUp(EXPORT_DIR);
    $filename = exportCSV($deviceId, $data, EXPORT_DIR);

    $ret = array(
        'html' => $html,
        'title' => 'Tabular data for #'.$deviceId,
        'csv_link' => $filename
    );

else :
    $ret = array();
endif;


echo json_encode($ret);