<?php 

//    Straightforward CRUD implementation for a basic Task object, implemented
  //  in add(), index() [retrieve ALL], edit_name()/edit_complete(), and remove(),
  //  respectively.
  //  Although every add/edit/destroy operation does update the DB, the only  
  //  actual POST or GET requests occur on index(). The objective is for the
  //  view to update incrementally based on the encoded JSON data, and for this
  //  (at all times) to be identical to a fully-refreshed view.  
class Tasks extends CI_Controller
{
  //    Retrieve all tasks (complete or not) and load them into the view.
  public function index()
  {
    $this->load->model('task_model');
    $tasks = $this->task_model->retrieve_all_tasks();
    $error_string = $this->session->flashdata('error');

    $viewData['tasks'] = $tasks;
    $viewData['errors'] = $error_string;

    $this->load->view('task_view', $viewData);
  }

  //    Create a new 'to be completed' task, from the form data.
    //  Send task info to the view, so it can update w/out POST.
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
        $task['complete'] = 0;      //  new tasks are not yet complete
      }
    }
    else
    {
      $task['errors'] = validation_errors();
    }
    echo json_encode($task);
  }

  //    Edit the name of this task, from the form data.
    //  Send updated task info to the view, so it can update w/out POST.
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

  //    Toggle the state of this task's 'complete' state.  
    //  Send updated task info to the view, so it can update w/out POST.
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

  //    Destroy task and send info to view so it can update w/out POST.
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
