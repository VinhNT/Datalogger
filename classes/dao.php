<?php
class Dao {
	private $table = 'tbldevicedata';
    private $table_2 = 'chart_detail';
	
	public function Dao() {
		$link = @mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD);
		if (!$link) {
			echo 'Cannot connect to DB';
			die;
		}
		$db = @mysql_select_db(DB_NAME);
		if (!$db) {
			echo 'Cannot select DB';
			die;
		}
	}

	public function beginTransaction() {
		mysql_query("START TRANSACTION");
		
		return mysql_error();
	}
	public function commitTransaction() {
		mysql_query("COMMIT");
	}
	public function rollbackTransaction() {
		mysql_query("ROLLBACK");
	}

    /*
     * get DeviceData
     * @param string $deviceId
     * @param string $where
	 * @param string $limit
     *
     * @return array
     */
    public function getDeviceData($deviceId, $where='', $limit='') {
        $sql = 'SELECT Data, RecordDate
                FROM '.$this->table.'
                WHERE DeviceID = "'.$deviceId.'"
        ';
		if (!empty($where)) {
			$sql .= $where;
		}
		if (!empty($limit)) {
			$sql .= $limit;
		}

        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_assoc($res)) {
			$result[$row['RecordDate']] = $row['Data'];
		}

        ksort($result); // sort by RecordDate
		return $result;
    }

    /*
     * get DeviceData
     * @param string $deviceId
     *
     * @return array
     */
    public function getDeviceChartDetail($deviceId, $sort=true) {
        $sql = 'SELECT 	DeviceID, DataCol, Type, Title, SubTitle, YAxisLabel, `Order`, Status, MinYValue, MaxYValue 
                FROM '.$this->table_2.'
                WHERE DeviceID = "'.$deviceId.'" AND Status = 1
        ';
        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_assoc($res)) {
			$result[$row['Order']] = $row;
		}
        
        if ($sort) ksort($result); // sort by Order
		
        return $result;
    }
    
    public function countDeviceData($deviceId) {
        $sql = 'SELECT count(*) as total
                FROM '.$this->table.'
                WHERE DeviceID = "'.$deviceId.'"
        ';

        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);

		return $row['total'];
    }
	
	public function getDeviceFullInfo($deviceId, $start='', $end='', $limit='', $sort=true) {
		$sql = 'Select * From '.$this->table.'
				Where DeviceID = "'.$deviceId.'"
		';
		
		if (!empty($start)) {
            $sql .= ' AND RecordDate >= "'.$start.'"';
        }
        if (!empty($end)) {
            $sql .= ' AND RecordDate <= "'.$end.'"';
        }
        if (!empty($limit)) $sql .= $limit;

        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_assoc($res)) {
			$result[$row['RecordDate']] = $row;
		}

        if ($sort) ksort($result); // sort by RecordDate
		return $result;
	}
	
	public function getUserByUnAndPwd($un, $pwd) {
		$sql = 'Select *
				From tbluser
				Where UserName = "'.$un.'" And Password = "'.$pwd.'"
		';

		$res = mysql_query($sql);
        $row = mysql_fetch_object($res);
		
		return $row;
	}
	public function getAllUsers() {
		$sql = 'SELECT *
                FROM tbluser
				ORDER BY UserName ASC;
        ';
        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_object($res)) {
			$result[] = $row;
		}
        
        return $result;
	}
	public function getUser($un) {
		$sql = 'Select *
                From tbluser
				Where UserName = "'.$un.'"
        ';
        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_object($res)) {
			$result[] = $row;
		}
        
        return $result;
	}
	public function addNewUser($data) {
		$sql = "Insert Into tbluser(UserName, FullName, Email, PhoneNumber, Organization, Details, RegisteredDate, Password) Values(
			'{$data['username']}',
			'{$data['fullname']}',
			'{$data['email']}',
			'{$data['phonenumber']}',
			'{$data['organization']}',
			'{$data['detail']}',
			'".date('Y-m-d')."',
			'{$data['password']}'
		)";
		mysql_query($sql);
	}
	public function updateUser($data) {
		$sql = "Update tbluser 
				Set
					UserName = '{$data['username']}',
					FullName = '{$data['fullname']}',
					Email    = '{$data['email']}',
					PhoneNumber = '{$data['phonenumber']}',
					Organization = '{$data['organization']}',
					Details = '{$data['detail']}'
		";
		if (!empty($data['password'])) {
			$sql .= ' , Password = "'.$data['password'].'"';
		}
		$sql .= " Where UserName = '{$data['currentUN']}'";

		mysql_query($sql);
	}
	public function removeUser($username){
		$sql = "Delete From tbluser 
				Where UserName = '{$username}'
		";

		mysql_query($sql);
	}
	
	public function getDeviceByID($DeviceID){
		$sql = 'Select *
                From tblloggerdevice
				Where DeviceID = "'.$DeviceID.'"
        ';
        $res = mysql_query($sql);
		$row = mysql_fetch_object($res);

        return $row;
	}
	public function getAllDevices($groupID=''){
		$sql = 'Select *
                From tblloggerdevice
        ';
		if (!empty($groupID)) $sql .= ' Where DeviceGroupID In ('.$groupID.')';
		$sql .= ' Order By DeviceID Asc';
        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_object($res)) {
			$result[] = $row;
		}
        
        return $result;
	}
	public function removeDevice($deviceid){
		$sql = "Delete From tblloggerdevice 
				Where DeviceID = '{$deviceid}'
		";
		mysql_query($sql);
	}
	public function addNewDevice($data) {
		$sql = "Insert Into tblloggerdevice(DeviceID, DeviceGroupID, DeviceName, DeviceDescription, DevicePosition, ChartRange, RecordInterval) Values(
			'{$data['deviceid']}',
			'{$data['devicegroupid']}',
			'{$data['devicename']}',
			'{$data['devicedescription']}',
			'{$data['deviceposition']}',
			'{$data['chartrange']}',
			'{$data['recordinterval']}'
		)";
		mysql_query($sql);
	}
	public function updateDevice($data) {
		$sql = "Update tblloggerdevice 
				Set
					DeviceID = '{$data['deviceid']}',
					DeviceGroupID = '{$data['devicegroupid']}',
					DeviceName    = '{$data['devicename']}',
					DeviceDescription = '{$data['devicedescription']}',
					DevicePosition = '{$data['deviceposition']}',
					ChartRange = '{$data['chartrange']}',
					RecordInterval = '{$data['recordinterval']}'
				 Where DeviceID = '{$data['currentDI']}'
		";

		mysql_query($sql);
	}
	
	public function getAllDeviceGroups($groupID=''){
		$sql = 'Select *
                From tbldevicegroup
        ';
		if (!empty($groupID)) $sql .= ' Where GroupID In ('.$groupID.')';
		$sql .= ' Order By GroupID Asc';
        $res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_object($res)) {
			$result[$row->GroupID] = $row;
		}
        
        return $result;
	}
	public function removeGroup($groupid){
		$sql = "Delete From tbldevicegroup 
				Where GroupID = '{$groupid}'
		";
		mysql_query($sql);
	}
	public function addNewGroup($data) {
		$sql = "Insert Into tbldevicegroup(GroupID, GroupName, GroupDescription) Values(
			'{$data['groupid']}',
			'{$data['groupname']}',
			'{$data['groupdescription']}'
		)";
		mysql_query($sql);
	}
	public function updateGroup($data) {
		$sql = "Update tbldevicegroup 
				Set
					GroupID = '{$data['groupid']}',
					GroupName = '{$data['groupname']}',
					GroupDescription    = '{$data['groupdescription']}'
				 Where GroupID = '{$data['currentGI']}'
		";

		mysql_query($sql);
	}
	
	public function deletePermissions() {
		$sql = "Truncate tblusergrouppermission ";
		mysql_query($sql);
	}
	public function savePermissions($data) {
		$_data = '';
		foreach ($data as $ug => $perms) {
			$ug = explode('-', $ug);
			$_data .= "('{$ug[1]}', '{$ug[0]}',";
			
			if (count($perms) == 2) {
				$_data .= "1, 1";
			} else {
				if ($perms[0] == 'r') $_data .= "1, 0";
				else $_data .= "0, 1";
			}
			$_data .= '),';
		}
		$_data = substr($_data, 0, -1);
		
		$sql = "Insert Into tblusergrouppermission(UserName, GroupID, CanRead, CanModify)
				Values {$_data}
		";

		mysql_query($sql);
	}
	public function getPermissions() {
		$sql = 'Select *
                From tblusergrouppermission
        ';
        $res = mysql_query($sql);

        $result = array();
		while ($obj = mysql_fetch_object($res)) {
			$result[$obj->GroupID.'_'.$obj->UserName.'_r'] = $obj->CanRead;
			$result[$obj->GroupID.'_'.$obj->UserName.'_m'] = $obj->CanModify;
		}
        
        return $result;
	}
	public function getUserPermission($un, $r='', $m='') {
		$sql = 'Select GroupID
                From tblusergrouppermission
				Where UserName = "'.$un.'"
		';
		if ($r) $sql .= ' And CanRead = 1';
		if ($m) $sql .= ' And CanModify = 1';

		$res = mysql_query($sql);

        $result = array();
		while ($row = mysql_fetch_array($res)) {
			$result[] = $row['GroupID'];
		}
        return $result;
	}
	public function updateUserSystemPermissions($un, $perm) {
		$sql = 'Update tbluser 
				Set 
					SystemPermission = "'.$perm.'"
				Where UserName = "'.$un.'"
		';
		var_dump($sql);
		mysql_query($sql);
	}
	
	public function isEmailExists($email, $currEmail) {
		$sql = 'Select count(*) as total From tbluser
				Where Email = "'.$email.'"
		';

		$res = mysql_query($sql);
		$row = mysql_fetch_array($res);
		
		return $row['total'];
	}
	
	public function getChartDetails($id){
		$sql = 'Select DeviceID, DataCol, `Type`, Title, SubTitle, YAxisLabel, `Order`, `Status` 
				From chart_detail
				Where DeviceID = "'.$id.'"
				Order By `Order`
		';

		$res = mysql_query($sql);
		$result = array();
		while ($row = mysql_fetch_array($res)){
			$result[] = $row;
		}

		return $result;
	}
	
	public function deleteChartDetail($id){
		$sql = 'Delete From chart_detail
				Where DeviceID = "'.$id.'"
        ';
        mysql_query($sql);
	}
	
	public function insertChartDetail($data) {
		$sql = "Insert Into chart_detail Values(
				'{$data[0]}',
				'{$data[1]}',
				'{$data[2]}',
				'{$data[3]}',
				'{$data[4]}',
				'{$data[5]}',
				'{$data[6]}',
				'{$data[7]}'
		);";
		mysql_query($sql);
	}
	
	
	
	function __insertChartData($data){
		$sql = "Insert Into tbldevicedata Values(
				'{$data[0]}',
				'{$data[1]}',
				'{$data[2]}',
				'{$data[3]}',
				'{$data[4]}'
		);";
		mysql_query($sql);
	}
	function __truncateTable($tbl){
		mysql_query("Truncate {$tbl}");
	}

}

?>