<h2>Create Projects<h2> 


<?php $attributes = array('id'=>'create_form', 'class'=>'form_horizontal'); ?> <!-- 'login_form is the 'id' for using in css control -->

<?php echo validation_errors("<p class = 'bg-danger'>"); ?>

<?php if($this->session->flashdata('errors')): ?>
<?php echo $this->session->flashdata('errors'); ?>	
<?php endif; ?>


<?php echo form_open('projects/create', $attributes);?> <!-- this is just a communication with users controller in login function
 -->
<div class="form-group">
<?php echo form_label('Project Name'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'project_name',
	'placeholder' => 'Enter Project Name'

	);			
?>

<?php echo form_input($data)//functon for input normal things that do not need to hide contents?>

</div>


<div class="form-group">
<?php echo form_label('Project Description'); ?>

<?php $data = array(

	'class' => 'form-control',//more beautiful form, form-control is a boostrap class
	'name' => 'project_body'
	
	);			
?>

<?php echo form_textarea($data);//functon for input normal things that do not need to hide contents?>

</div>



<div class="form-group">

<?php $data = array(

	'class' => 'btn btn-primary',//more beautiful form, btn btn-primary is a boostrap class
	'name' => 'submit',
	'value' => 'Create'
	);
?>
	
<?php echo form_submit($data) //functon for submit?> 

</div>

<?php echo form_close();?>
