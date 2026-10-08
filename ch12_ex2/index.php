<?php
// 1 năm = 365 ngày * 24 giờ * 60 phút * 60 giây = 31,536,000 giây
$lifetime = 60 * 60 * 24 * 365; 
session_set_cookie_params($lifetime);
session_start();
// Code mới dùng Session
if (!isset($_SESSION['tasklist'])) {
    $_SESSION['tasklist'] = array();
}
$task_list = $_SESSION['tasklist'];
$action = filter_input(INPUT_POST, 'action');
$errors = array();

switch( $action ) {
    case 'add':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            $task_list[] = $new_task;
        }
        break;
    case 'delete':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === NULL || $task_index === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            unset($task_list[$task_index]);
            $task_list = array_values($task_list);
        }
        break;
}

include('task_list.php');
$_SESSION['tasklist'] = $task_list;
?>