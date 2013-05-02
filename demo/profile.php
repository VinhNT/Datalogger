<?php
	require_once '../classes/Membership.php';
	require_once '../classes/conf.php';
	require_once '../classes/dao.php';
	$membership = new Membership();
	$membership->confirm_Member();
	
	$user = $_SESSION['user'];
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="images/favicon.ico"/>
    
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/default.css" />
	<script>action="";</script>
	<?php include_once('_js_footer.php'); ?>
    <title>Edit profile | Data logger</title>
</head>

<body class="sys">
<?php include_once('_header.php'); ?>

<div class="sys_setting_wrapper clearfix">
	<h2>Edit your profile</h2>
    <form id="edit_profile" class="edit_profile">
        <div class="line">
            User name <span class="required">*</span><br />
            <input type="text" id="username" name="username" readonly="readonly" value="<?= $user->UserName; ?>"/>
        </div>
        <div class="line">
            Full name <span class="required">*</span><br />
            <input type="text" id="fullname" name="fullname" value="<?= $user->FullName; ?>" />
        </div>
        <div class="line">
            Email <span class="required">*</span><br />
            <input type="text" id="email" name="email" value="<?= $user->Email; ?>" />
            <input type="hidden" name="cur_email" id="cur_email" value="<?= $user->Email; ?>"  />
        </div>
        <div class="line">
            Phone number<br />
            <input type="text" id="phonenumber" name="phonenumber" value="<?= $user->PhoneNumber; ?>"/>
        </div>
        <div class="line">
            Organization<br />
            <input type="text" id="organization" name="organization" value="<?= $user->Organization; ?>" />
        </div>
        <div class="line">
            Detail<br />
            <textarea id="detail" name="detail"><?= $user->Details; ?></textarea>
        </div>
        <div class="line password">
            Password <br />
            <input type="password" id="password" name="password"  />
        </div>
        <div class="line password">
            Re-type password <br />
            <input type="password" id="re-password" />
        </div>

        
        <input type="button" value="Update" id="updateProfile"/>
    </form>
</div>

<script type="text/javascript">
	$('#updateProfile').click(function(){
		_checkUserForm();
	});
	function _checkUserForm(){
		
		var _fullname = $('#fullname');
		if (_fullname.val() == '') {
			alert('Please enter fullname');
			return false;
		}
		var _email = $('#email');
		if (_email.val() == '') {
			alert('Please enter email');
			return false;
		}
		if (_isValidEmail(_email.val())) {
			alert('Please enter a valid email');
			return false;
		}
		
	
		var _password = $('#password');
		if (_password.val() != '') {
			var _re_password = $('#re-password');
			if (_re_password.val() == '') {
				alert('Please re-type password');
				return false;
			}
			if (_password.val() != _re_password.val()) {
				alert('Password not match. Please check again');
				return false;
			}
		}
	
		
		var op = 'updateProfile';
		$.get(
			'user_dao.php',
			{
				data: $('#edit_profile.edit_profile').serialize(),
				op: op,
				currentUN: $('#username').val(),
				currentEM: $('#cur_email').val()
			},
			function(data){
				//console.log(data);
				
				if (data == 1) {
					alert('Your profile has been updated!');
					$('#cur_email').val(_email.val());
				} else {
					alert(data);
				}
			}
		);
		return false;
	}
	function _isValidEmail(mail) {
		var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(mail)) return false;
		else return true;
	}
</script>
</body>
</html>