<h2>Register<h2> 


<?php $attributes = array('id'=>'register_form', 'class'=>'form_horizontal'); ?> <!-- 'login_form is the 'id' for using in css control -->

<?php echo validation_errors("<p class = 'bg-danger'>"); ?>

<?php if($this->session->flashdata('errors')): ?>
<?php echo $this->session->flashdata('errors'); ?>	
<?php endif; ?>


<?php echo form_open('users/register', $attributes);?> <!-- this is just a communication with users controller in login function
 -->
<div class="form-group">
<?php echo form_label('First Name'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'first_name',
	'placeholder' => 'Enter First Name'
	);			
?>

<?php echo form_input($data)//functon for input normal things that do not need to hide contents?>

</div>



<div class="form-group">
<?php echo form_label('Last Name'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'last_name',
	'placeholder' => 'Enter Last Name'
	);			
?>

<?php echo form_input($data)//functon for input normal things that do not need to hide contents?>

</div>


<div class="form-group">
<?php echo form_label('Email'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'email',
	'placeholder' => 'Enter Your Email'
	);			
?>

<?php echo form_input($data)//functon for input normal things that do not need to hide contents?>

</div>



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
	'value' => 'Register'
	);
?>
	
<?php echo form_submit($data) //functon for submit?> 

</div>

<?php echo form_close();?>
