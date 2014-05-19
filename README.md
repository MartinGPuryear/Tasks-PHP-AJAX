Tasks-PHP-AJAX
Martin G. Puryear
==============

 Tasks-PHP-AJAX - tracks Tasks on a todo list, w incremental UI update.

 Demonstrates PHP, MVC (CodeIgniter), AJAX, JQueryUI, and Bootstrap3,
as well as MySql. 

---

 Tasks controller handles create/retrieve/update/destroy for tasks,
passing the initial list of objects as a large chunk, but for any
subsequent updates providing info via AJAX so the view can update 
incrementally. 

 Task model includes 'name' (txt) and 'complete' (bool), so a task
can be marked as complete without being deleted. 

 Task view allows for in-place edit of the task names, using JQuery
to hook the click and focusout events. Also, the task list can be
click/dragged to resort the list, using .sortable from JQueryUI.

---

 Entity Relationship Diagram and raw SQL DB files are included as well:
application\models\tasks.mwb and application\models\tasks_schema.sql.
 
