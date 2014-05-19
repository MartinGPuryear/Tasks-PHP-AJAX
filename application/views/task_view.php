<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="validation MVC assignment page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="author" content="Martin Puryear" />

	<title>Task Manager</title>
	
	<link type="text/css" rel="stylesheet" href="<?= base_url('external/jQueryUI/v1.10.3/themes/smoothness/jquery-UI.css') ?>" />	
	<link type="text/css" rel="stylesheet" href="<?= base_url('external/bootstrap-3.0.3-dist/dist/css/bootstrap.min.css') ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/tasks.css') ?>" />

	<script type="text/javascript" src="<?= base_url('external/JQuery/v2.0.3/jquery.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('external/bootstrap-3.0.3-dist/dist/js/bootstrap.min.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('external/jQueryUI/v1.10.3/jquery-ui.js') ?>"></script>

	<script type="text/javascript">

	$(document).ready(function() {

		$("#tasks-div").sortable({ opacity: 0.5 });

		$("#tasks-div").on('focusout', '.edit-task-name', function() {
			$.post(	
				$(this).attr('action'),
				$(this).serialize(), 
				function(task) {
					$('#name-txt-'+task.id).text(task.name).show().next().hide().val(task.name);
					$('#errors').html(task.errors);
				},
				"json"
			);
			return false;
		});

		$("#tasks-div").on('click', '.txt-task-name', function(e) {
			e.preventDefault();
			$(this).hide().next().val($(this).text()).show().focus();
		});

		$("#tasks-div").on('click', '.edit-task-complete', function() {
			$.post(	
				$(this).attr('action'),
				$(this).serialize(), 
				function(task) {
					
					$('#complete-'+task.id).prop('checked', task.complete);	
					if (task.complete)
					{
						$('#name-txt-'+task.id).addClass("txt-task-name-complete");
					}
					else
					{
						$('#name-txt-'+task.id).removeClass("txt-task-name-complete").click();
					}

					$('#errors').html(task.errors);
				},
				"json"
			);
			return false;
		});

		$('#tasks-div').on('click', '.remove-task', function() {
			$.post(	
				$(this).attr('action'),
				$(this).serialize(), 
				function(task) {
					if (task.errors === null)
					{
						$('#task-'+task.id).remove();
					}
					$('#errors').html(task.errors);
				},
				"json"
			);
			return false;
		});

		$("#add-task").on('submit', function() {
			
 			$.post(	
				$(this).attr('action'),
				$(this).serialize(), 
				function(task) {

					if (task.errors === null)
					{
						var task_open       = "<li class='col-xs-6 task' id='task-" + task.id + "'>";
						var task_close      = "</li>";

						var rm_button = "<button class='btn btn-danger btn-xs remove-task' action='<?= base_url('tasks/remove/" + task.id + "') ?>'>remove</button> ";
						var disable_box = "<input type='checkbox' id='complete-" + task.id + "' class='edit-task-complete' action='<?= base_url('tasks/edit_complete/" + task.id + "') ?>' name='task-complete' value='complete' /> ";
						var task_txt = "<span for='complete-" + task.id + "' id='name-txt-" + task.id + "' class='txt-task-name' >" + task.name + "</span> ";
						var task_input = "<input type='text' class='edit-task-name' action='<?= base_url('tasks/edit_name/" + task.id + "') ?>' name='task-name' id='name-in-" + task.id + "' value='" + task.name + "' hidden />";

						var complete_task  = task_open + rm_button + disable_box + task_txt + task_input + task_close;

						$('#tasks-div').append(complete_task);
					}
					$('#errors').html(task.errors);
				},
				"json"
			);
			$('#input-text').val("");

			return false;
		});

		return true;
	});

	</script>


</head>
<body>
	<div class="container">
		<h2>My Tasks</h2>
		<h5>(click on a task's text, to edit)</h5>
		<div id="errors">
			<?= $errors ?>
		</div>
		<ul id="tasks-div" class="col-xs-8">

<?php	foreach ($tasks as $task) 	{		?>
			<li class="col-xs-6 task" id="task-<?= $task['id'] ?>">
				<button class='btn btn-danger btn-xs remove-task' action="<?= base_url("tasks/remove/{$task['id']}") ?>">remove</button>
<?php		if ($task['complete']) { 		?>
				<input type="checkbox" id="complete-<?= $task['id'] ?>" class="edit-task-complete" action="<?= base_url("tasks/edit_complete/{$task['id']}") ?>" name="task-complete" value="complete" checked />
				<span for="complete-<?= $task['id'] ?>" id="name-txt-<?= $task['id'] ?>" class="txt-task-name txt-task-name-complete" ><?= $task['name'] ?></span>
<?php		} else { 						?>
				<input type="checkbox" id="complete-<?= $task['id'] ?>" class="edit-task-complete" action="<?= base_url("tasks/edit_complete/{$task['id']}") ?>" name="task-complete" value="complete"  />
				<span for="complete-<?= $task['id'] ?>" id="name-txt-<?= $task['id'] ?>" class="txt-task-name" ><?= $task['name'] ?></span>
<?php 		}								?>
				<input type="text" class="edit-task-name" action="<?= base_url("tasks/edit_name/{$task['id']}") ?>" name="task-name" id="name-in-<?= $task['id'] ?>" value="<?= $task['name'] ?>" hidden />
			</li> 
<?php	}									?>
		</ul>

		<div class="col-xs-4">
			<form action="<?= base_url('tasks/add') ?>" class="form-horizontal" id="add-task" method="post" role="form">
				<label for="input-text">Add a task:</label>
				<input type="text" name="input-text" class="form-control" id="input-text" placeholder='Type task here' autofocus />
				<input class="btn btn-primary" type="submit" value="Add Task" />
			</form>
		</div>
	</div>	<!-- container -->
</body>
</html>