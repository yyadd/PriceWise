<p class="bg-success">

<?php if($this->session->flashdata('login_success')):?>

<?php echo $this->session->flashdata('login_success'); ?>	

<?php endif; ?>


<?php if($this->session->flashdata('user_registered')):?>

<?php echo $this->session->flashdata('user_registered'); ?>	

<?php endif; ?>


</p>

<p class="bg-danger">

<?php if($this->session->flashdata('login_failed')):?>

<?php echo $this->session->flashdata('login_failed'); ?>	

<?php endif; ?>

<?php if($this->session->flashdata('no_access')):?>

<?php echo $this->session->flashdata('no_access'); ?>	

<?php endif; ?>

</p>

<div class="jumbotron">

 <h2 class="text-center">Welcome to the CI APP</h2>

</div>

<?php if(isset($projects)): ?>

<h1> WishList</h1>

<table class="table table-bordered">

	<thead>

		<tr>
			<th>
			Project Name	
			</th>
			<th>
			Project Description	
			</th>
		</tr>
	</thead>
	<tbody>

		<?php foreach($projects as $project): ?>

		<tr>

		<td><?php echo $project->project_name; ?></td>

		<td><?php echo $project->project_body; ?></td>

		<td><a href="<?php echo base_url();?>projects">View</a></td>
	

		</tr>

		<?php endforeach; ?>

	</tbody>
</table>
<?php endif; ?>
