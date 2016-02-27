<?php if($this->session->userdata('logged_in')): ?>

<h2> logout </h2>
<?php echo form_open('users/logout') ?>
<p>
<?php if($this->session->userdata('username')): ?>
<?php echo "You are logged in as " . $this->session->userdata('username') ?>
<?php endif; ?>
</p>
<?php

$data = array(
	'class' => 'btn btn-primary',
	'name' => 'submit',
	'value' => 'Logout'
	);

?>

<?php echo form_submit($data); ?>
<?php echo form_close(); ?>

<?php else: ?>

<h2>Login form<h2> 

<?php $attributes = array('id'=>'login_form', 'class'=>'form_horizontal'); ?> <!-- 'login_form is the 'id' for using in css control -->
<?php if($this->session->flashdata('errors')): ?>
<?php echo $this->session->flashdata('errors'); ?>	
<?php endif; ?>


<?php echo form_open('users/login', $attributes);?> <!-- this is just a communication with users controller in login function
 -->
<div class="form-group">
<?php echo form_label('Username'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'username',
	'placeholder' => 'Enter Usename'
	);			
?>

<?php echo form_input($data)//functon for input normal things that do not need to hide contents?>

</div>



<div class="form-group">
<?php echo form_label('Password'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'password',
	'placeholder' => 'Enter Password'
	);
?>
	
<?php echo form_password($data) //functon for input password?>
</div>

<div class="form-group">
<?php echo form_label('Confirm Password'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'confirm_password',
	'placeholder' => 'Renter Password'
	);
?>
	
<?php echo form_password($data) //functon for input password?> 
</div> 


<div class="form-group">

<?php $data = array(

	'class' => 'btn btn-primary',//more beautiful form, btn btn-primary is a boostrap class
	'name' => 'submit',
	'value' => 'Login'
	);
?>
	
<?php echo form_submit($data) //functon for submit?> 

</div>

<?php echo form_close();?>


<?php endif; ?>
