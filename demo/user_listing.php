<?php
require_once '../classes/Membership.php';
require_once '../classes/conf.php';
require_once '../classes/dao.php';

$membership = new Membership();	
$dao = new Dao();
$users = $dao->getAllUsers();
$permissionMgr = $membership->can_User_Access_System_Settings('permissionMgr');

?>

<div class="data_listing">
    <h2>All users </h2>
    <table width="100%">
    	<tr class="header">
        	<td>UserName</td>
            <td>Email</td>
            <td>Action</td>
        </tr>
        
        <?php foreach ($users as $u): ?>
        <tr id="tr_<?= $u->UserName; ?>">
            <td><?= $u->UserName; ?></td>
            <td><a href="mailto: <?= $u->Email; ?>" title="<?= $u->FullName; ?>"><?= $u->Email; ?></a></td>
            <td class="__data">
            	<a href="#" class="edit_user" data-id="<?= $u->UserName; ?>">Edit</a>
                <?php if ($permissionMgr): ?>
                &nbsp;
                <a href="permission.php?u=<?= $u->UserName; ?>">Permission</a>
                <?php endif; ?>
                &nbsp;
                <a href="#" class="delete_user" data-id="<?= $u->UserName; ?>">Remove</a>
                
                <input type="hidden" id="<?= $u->UserName; ?>_UserName" value="<?= $u->UserName; ?>" />
                <input type="hidden" id="<?= $u->UserName; ?>_FullName" value="<?= $u->FullName; ?>" />
                <input type="hidden" id="<?= $u->UserName; ?>_Email" value="<?= $u->Email; ?>" />
                <input type="hidden" id="<?= $u->UserName; ?>_PhoneNumber" value="<?= $u->PhoneNumber; ?>" />
                <input type="hidden" id="<?= $u->UserName; ?>_Organization" value="<?= $u->Organization; ?>" />
                <input type="hidden" id="<?= $u->UserName; ?>_Details" value="<?= $u->Details; ?>" />
                
                <input type="hidden" class="reserve_un" id="<?= $u->UserName; ?>" value="<?= $u->UserName; ?>" />
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="edit_form_wrapper">
    <h2>Add / Edit user</h2>
    <form id="user">
        <div class="line">
            User name <span class="required">*</span><br />
            <input type="text" id="username" name="username" readonly="readonly" />
            <input type="hidden" id="current_username" />
        </div>
        <div class="line">
            Full name <span class="required">*</span><br />
            <input type="text" id="fullname" name="fullname" />
        </div>
        <div class="line">
            Email <span class="required">*</span><br />
            <input type="text" id="email" name="email" />
        </div>
        <div class="line">
            Phone number<br />
            <input type="text" id="phonenumber" name="phonenumber" />
        </div>
        <div class="line">
            Organization<br />
            <input type="text" id="organization" name="organization" />
        </div>
        <div class="line">
            Detail<br />
            <textarea id="detail" name="detail"></textarea>
        </div>
        <div class="line password">
            Password <span class="required">*</span><br />
            <input type="password" id="password" name="password" />
        </div>
        <div class="line password">
            Re-type password <span class="required">*</span><br />
            <input type="password" id="re-password" />
        </div>

        <input type="button" class="insert" value="Add new user" />
        
        <input type="button" class="update hidden" value="Update" />
        <a href="#" class="cancel hidden">Cancel</a>
    </form>

</div>


<script>
	$(document).ready(function(){
		initEditUser();
	});
	
	function initEditUser(){
		$('.delete_user').click(function(){
			if (confirm('Are you sure want to remove this user?')) {
				var _o = $(this);
				var id = _o.attr('data-id');
				$.get(
					'user_dao.php',
					{
						data: '',
						op: 'delete',
						username: id
					},
					function(data){
						$('#tr_'+id).remove();
					}
				);
			}
		});
		
		$('.edit_user').click(function(){
			document.getElementById('user').reset();
			
			var _o = $(this);
			var id = _o.attr('data-id');
			
			$('#username').val($('#'+id+'_UserName').val());
			$('#fullname').val($('#'+id+'_FullName').val());
			$('#email').val($('#'+id+'_Email').val());
			$('#phonenumber').val($('#'+id+'_PhoneNumber').val());
			$('#organization').val($('#'+id+'_Organization').val());
			$('#detail').html($('#'+id+'_Details').val());
			$('#current_username').val(id);
	
			$('#user .password .required').hide();
			$('#user .insert').hide();
			$('#user .update').show();
			$('#user .cancel').show();
		});
		$('#user .cancel').click(function(){
			document.getElementById('user').reset();
			$('#detail').html('');
			$('#current_username').val('');
	
			
			$('#user .password .required').show();
			$('#user .insert').show();
			$('#user .update').hide();
			$('#user .cancel').hide();
		});
		
		$('#user .insert').click(function(){
			_checkUserForm(true);
		});
		$('#user .update').click(function(){
			_checkUserForm(false);
		});
	}
	function _checkUserForm(isInsert){
		var _username = $('#username');
		if (_username.val() == '') {
			alert('Please enter username');
			return false;
		}
		// check if new user name is duplicate or not
		var usernameArr = $('.reserve_un');
		if (!isInsert) { // no need to check current username
			var _username_to_check = $('#current_username').val();
			for (var i=0; i<usernameArr.length; i++) {
				if ($(usernameArr[i]).val() == _username_to_check) {
					usernameArr.splice(i, 1);
					break;
				}
			}
		}
		for (var i=0; i<usernameArr.length; i++) {
			if (_username.val() == $(usernameArr[i]).val()) {
				alert('Given username is already taken. Please try again');
				return false;
			}
		}
		
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
		
		if (isInsert) { 
			// check password as well
			var _password = $('#password');
			if (_password.val() == '') {
				alert('Please enter password');
				return false;
			}
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
		
		var op = 'update';
		if (isInsert) op = 'insert';
		$.get(
			'user_dao.php',
			{
				data: $('#user').serialize(),
				op: op,
				currentUN: $('#current_username').val()
			},
			function(data){
				//console.log(data);
				
				if (data == 1) {
					if (isInsert) {
						alert('New user created');
					} else {
						alert('User updated');
					}
					
					$('.sys_setting_wrapper').load('user_listing.php');
				} else {
					alert('Error occurs - pls try again');
				}
				document.getElementById('user').reset();
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