<?php

date_default_timezone_set("America/Los_Angeles");

class Task_Model extends CI_Model
{
    function create_task($task)
    {
        $new_id = NULL;
        $now = date("Y-m-d, H:i:s");
        $query = "INSERT INTO tasks (name, complete, created_at, updated_at) 
                  VALUES (?,?,?,?)";

        $values = array($task['name'], 0, $now, $now);  //  all tasks will begin life not yet complete

        if ($this->db->query($query, $values))
        {
            $new_id =  $this->db->insert_id();
        }
        return $new_id;
    }

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

    function retrieve_all_tasks()
    {
        $query = "SELECT
                      * 
                  FROM 
                      tasks t
                  ORDER BY t.complete, t.id";

        return $this->db->query($query)->result_array();
    }

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

    function destroy_task($task_id)
    {
       $query = "DELETE FROM tasks
                  WHERE
                      id = ?";
        
        $values = array($task_id); 

        return $this->db->query($query, $values);
    }
}

?>