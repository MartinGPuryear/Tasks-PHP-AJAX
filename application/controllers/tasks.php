<?php 

class Tasks extends CI_Controller
{
	public function index()
	{
		$this->load->model('task_model');
		$tasks = $this->task_model->retrieve_all_tasks();
		$error_string = $this->session->flashdata('error');

		$viewData['tasks'] = $tasks;
		$viewData['errors'] = $error_string;

		$this->load->view('task_view', $viewData);
	}

	public function add()
	{
		$task['errors'] = NULL;
		$this->load->library("form_validation");
		$this->form_validation->set_rules("input-text", "Task", "required");

		if ($this->form_validation->run())
		{
			$task['name'] = $this->input->post('input-text');

			$this->load->model('task_model');
			
			$task['id'] = $this->task_model->create_task($task);
			if ($task['id'] == NULL)
			{
				$task['errors'] = "Unable to add this task";
			}
			else
			{
				$task['complete'] = 0;
			}
		}
		else
		{
			$task['errors'] = validation_errors();
		}
		echo json_encode($task);
	}

	public function edit_name($task_id)
	{
		$this->load->model('task_model');
		$task = $this->task_model->retrieve_task($task_id);
		if (!$task)
		{
			$task['errors'] = "Unable to edit this task";
		}
		else
		{
			$task['errors'] = NULL;

			$new_name = $this->input->post('task-name');

			$this->load->library("form_validation");
			$this->form_validation->set_rules("task-name", "Task", "required");

			if ($this->form_validation->run())
			{
				$old_name = $task['name'];
				$task['name'] = $new_name;
				if (!$this->task_model->update_task_name($task))
				{
					$task['errors'] = "Unable to edit this task";
					$task['name'] = $old_name;
				}
			}
			else
			{
				$task['errors'] = validation_errors();
			}
		}

		echo json_encode($task);
	}

	public function edit_complete($task_id)
	{
		$this->load->model('task_model');
		$task = $this->task_model->retrieve_task($task_id);

		if (!$task)
		{
			$task['errors'] = "Unable to edit this task";
		}
		else
		{
			$task['errors'] = NULL;

			$old_complete = $task['complete'];
			$task['complete'] = ($this->input->post('task-complete') == 'complete');

			if (!$this->task_model->update_task_complete($task))
			{
				$task['errors'] = "Unable to edit this task";
				$task['complete'] = $old_complete;
			}
		}

		echo json_encode($task);
	}

	public function remove($task_id)
	{
		$task['errors'] = NULL;
		$this->load->model('task_model');

		if (!$this->task_model->destroy_task($task_id))
		{
			$task['errors'] = "Unable to remove this task";
		}
		else
		{
			 $task['id'] = $task_id;
		}

		echo json_encode($task);
	}
}
