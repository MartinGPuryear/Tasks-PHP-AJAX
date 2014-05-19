<?php

date_default_timezone_set("America/Los_Angeles");

class Task_Model extends CI_Model
{
    //    Create a task with the given info; return the new ID.
    function create_task($task)
    {
        $new_id = NULL;
        $now = date("Y-m-d, H:i:s");
        $query = "INSERT INTO tasks (name, complete, created_at, updated_at) 
                  VALUES (?,?,?,?)";

        $values = array($task['name'], 0, $now, $now);  //  All tasks begin life as not complete
                                                        //  Could also simply make 0 the DB default.
        if ($this->db->query($query, $values))
        {
            $new_id =  $this->db->insert_id();
        }
        return $new_id;
    }

    //  Retrieve the task with the given ID.
    function retrieve_task($task_id)
    {
        $query = "SELECT
                      * 
                  FROM 
                      tasks t
                  WHERE
                      t.id = ?";

        $values = array($task_id);

        return $this->db->query($query, $values)->row_array();
    }

    //    Retrieve all tasks - incomplete and complete.  
    function retrieve_all_tasks()
    {
        $query = "SELECT
                      * 
                  FROM 
                      tasks t
                  ORDER BY t.complete, t.id";

        return $this->db->query($query)->result_array();
    }

    //    Update the task with the given info.  
    function update_task($task)
    {
        $now = date("Y-m-d, H:i:s");
        $query = "UPDATE tasks t 
                  SET
                      t.name = ?, 
                      t.complete = ?, 
                      t.updated_at = ? 
                  WHERE
                      t.id = ?";

        $values = array($task['name'], $task['complete'], $now, $task['id']);

        return $this->db->query($query, $values);
    }

    //    Update the task with only the given name.  
    function update_task_name($task)
    {
        $now = date("Y-m-d, H:i:s");
        $query = "UPDATE tasks t 
                  SET
                      t.name = ?, 
                      t.updated_at = ? 
                  WHERE
                      t.id = ?";

        $values = array($task['name'], $now, $task['id']);

        return $this->db->query($query, $values);
    }

    //    Update the task with only the given completion state.  
    function update_task_complete($task)
    {
        $now = date("Y-m-d, H:i:s");
        $query = "UPDATE tasks t 
                  SET
                      t.complete = ?, 
                      t.updated_at = ? 
                  WHERE
                      t.id = ?";

        $values = array($task['complete'], $now, $task['id']);

        return $this->db->query($query, $values);
    }

    //    Destroy the task with the given ID.
    function destroy_task($task_id)
    {
       $query =  "DELETE FROM tasks
                  WHERE
                      id = ?";
        
        $values = array($task_id); 

        return $this->db->query($query, $values);
    }
}

?>