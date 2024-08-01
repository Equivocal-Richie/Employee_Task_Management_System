<?php

class Admin_Class
{
    public $db;
    public $pdo;


    public function fetch_admin_login($username, $password)
    {
        try {
            $sql = "SELECT * FROM tbl_admin WHERE username = :username AND password = :password ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $admin = $stmt->fetch();
            return $admin;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function manage_all_info($sql)
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function __construct($db)
{
    $this->db =$db;
    $host_name = 'localhost';
    $user_name = 'root';
    $password = '';
    $db_name = 'employee_task_management_system';

    try {
        $connection = new PDO("mysql:host={$host_name}; dbname={$db_name}", $user_name,  $password);
        $this->db = $connection; // connection established
        echo "Connection successful"; // add this line for debugging
    } catch (PDOException $message) {
        echo $message->getMessage();
    }
}

    public function test_form_input_data($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function admin_login_check($data)
    {
        $upass = $this->test_form_input_data(md5($data['admin_password']));
        $username = $this->test_form_input_data($data['username']);

        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE username=:uname AND password=:upass LIMIT 1");
            $stmt->execute([':uname' => $username, ':upass' => $upass]);
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                session_start();
                $_SESSION['admin_id'] = $userRow['user_id'];
                $_SESSION['name'] = $userRow['fullname'];
                $_SESSION['security_key'] = 'rewsgf@%^&*nmghjjkh';
                $_SESSION['user_role'] = $userRow['user_role'];
                $_SESSION['temp_password'] = $userRow['temp_password'];

                if ($userRow['temp_password'] == null) {
                    header('Location: task-info.php');
                } else {
                    header('Location: changePasswordForEmployee.php');
                }
            } else {
                $message = 'Invalid user name or Password';
                return $message;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function change_password_for_employee($data)
    {
        $password  = $this->test_form_input_data($data['password']);
        $re_password = $this->test_form_input_data($data['re_password']);
        $user_id = $this->test_form_input_data($data['user_id']);

        if ($password == $re_password) {
            try {
                $update_user = $this->db->prepare("UPDATE tbl_admin SET password = :x, temp_password = :y WHERE user_id = :id ");
                $update_user->bindParam(':x', $password);
                $update_user->bindParam(':y', '');
                $update_user->bindParam(':id', $user_id);
                $update_user->execute();

                header('Location: task-info.php');
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            $message = 'Sorry !! Password Can not match';
            return $message;
        }
    }

    public function admin_logout()
    {
        session_start();
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['security_key']);
        unset($_SESSION['user_role']);
        header('Location: index.php');
    }

    public function add_new_user($data)
    {
        $user_fullname  = $this->test_form_input_data($data['em_fullname']);
        $user_username = $this->test_form_input_data($data['em_username']);
        $user_email = $this->test_form_input_data($data['em_email']);
        $temp_password = rand(000000001, 10000000);
        $user_password = $this->test_form_input_data(md5($temp_password));
        $user_role = 2;

        try {
            $sqlEmail = "SELECT email FROM tbl_admin WHERE email = :email ";
            $stmtEmail = $this->db->prepare($sqlEmail);
            $stmtEmail->bindParam(':email', $user_email);
            $stmtEmail->execute();
            $emailCount = $stmtEmail->rowCount();

            $sqlUsername = "SELECT username FROM tbl_admin WHERE username = :username ";
            $stmtUsername = $this->db->prepare($sqlUsername);
            $stmtUsername->bindParam(':username', $user_username);
            $stmtUsername->execute();
            $usernameCount = $stmtUsername->rowCount();

            if ($emailCount > 0 && $usernameCount > 0) {
                $message = "Email and Password both are already taken";
                return $message;
            } elseif ($usernameCount > 0) {
                $message = "Username Already Taken";
                return $message;
            } elseif ($emailCount > 0) {
                $message = "Email Already Taken";
                return $message;
            } else {
                $add_user = $this->db->prepare("INSERT INTO tbl_admin (fullname, username, email, password, temp_password, user_role) VALUES (:x, :y, :z, :a, :b, :c) ");

                $add_user->bindParam(':x', $user_fullname);
                $add_user->bindParam(':y', $user_username);
                $add_user->bindParam(':z', $user_email);
                $add_user->bindParam(':a', $user_password);
                $add_user->bindParam(':b', $temp_password);
                $add_user->bindParam(':c', $user_role);

                $add_user->execute();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_user_data($data, $id)
    {
        $user_fullname  = $this->test_form_input_data($data['em_fullname']);
        $user_username = $this->test_form_input_data($data['em_username']);
        $user_email = $this->test_form_input_data($data['em_email']);

        try {
            $update_user = $this->db->prepare("UPDATE tbl_admin SET fullname = :x, username = :y, email = :z WHERE user_id = :id ");

            $update_user->bindParam(':x', $user_fullname);
            $update_user->bindParam(':y', $user_username);
            $update_user->bindParam(':z', $user_email);
            $update_user->bindParam(':id', $id);

            $update_user->execute();

            header('Location: admin-manage-user.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_admin_data($data, $id)
    {
        $user_fullname  = $this->test_form_input_data($data['em_fullname']);
        $user_username = $this->test_form_input_data($data['em_username']);
        $user_email = $this->test_form_input_data($data['em_email']);

        try {
            $update_user = $this->db->prepare("UPDATE tbl_admin SET fullname = :x, username = :y, email = :z WHERE user_id = :id ");

            $update_user->bindParam(':x', $user_fullname);
            $update_user->bindParam(':y', $user_username);
            $update_user->bindParam(':z', $user_email);
            $update_user->bindParam(':id', $id);

            $update_user->execute();

            header('Location: admin-manage-user.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete_user_data($id)
    {
        try {
            $delete_user = $this->db->prepare("DELETE FROM tbl_admin WHERE user_id = :id ");
            $delete_user->bindParam(':id', $id);
            $delete_user->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_all_user()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE user_role = 2");
            $stmt->execute();
            $user = $stmt->fetchAll();
            return $user;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_single_user($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE user_id = :id ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch();
            return $user;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_user_login($username, $password)
    {
        try {
            $sql = "SELECT * FROM tbl_admin WHERE username = :username AND password = :password ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $user = $stmt->fetch();
            return $user;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_admin_password($password, $id)
    {
        try {
            $update_password = $this->db->prepare("UPDATE tbl_admin SET password = :password WHERE user_id = :id ");
            $update_password->bindParam(':password', $password);
            $update_password->bindParam(':id', $id);
            $update_password->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_user_login_email($email)
    {
        try {
            $sql = "SELECT * FROM tbl_admin WHERE email = :email ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();
            return $user;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_admin_data($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE user_id = :id ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $admin = $stmt->fetch();
            return $admin;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_admin_data_email($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE email = :email ");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $admin = $stmt->fetch();
            return $admin;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_admin_email($email, $id)
    {
        try {
            $update_email = $this->db->prepare("UPDATE tbl_admin SET email = :email WHERE user_id = :id ");
            $update_email->bindParam(':email', $email);
            $update_email->bindParam(':id', $id);
            $update_email->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_user_email($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT email FROM tbl_admin WHERE user_id = :id ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $email = $stmt->fetch();
            return $email;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_user_role($id, $role)
    {
        try {
            $update_role = $this->db->prepare("UPDATE tbl_admin SET user_role = :role WHERE user_id = :id ");
            $update_role->bindParam(':role', $role);
            $update_role->bindParam(':id', $id);
            $update_role->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_all_staff()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE user_role = 1");
            $stmt->execute();
            $staff = $stmt->fetchAll();
            return $staff;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_single_staff($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM tbl_admin WHERE user_id = :id ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $staff = $stmt->fetch();
            return $staff;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_staff_role($id, $role)
    {
        try {
            $update_role = $this->db->prepare("UPDATE tbl_admin SET user_role = :role WHERE user_id = :id ");
            $update_role->bindParam(':role', $role);
            $update_role->bindParam(':id', $id);
            $update_role->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_staff_password($password, $id)
    {
        try {
            $update_password = $this->db->prepare("UPDATE tbl_admin SET password = :password WHERE user_id = :id ");
            $update_password->bindParam(':password', $password);
            $update_password->bindParam(':id', $id);
            $update_password->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_staff_email($email, $id)
    {
        try {
            $update_email = $this->db->prepare("UPDATE tbl_admin SET email = :email WHERE user_id = :id ");
            $update_email->bindParam(':email', $email);
            $update_email->bindParam(':id', $id);
            $update_email->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_staff_name($name, $id)
    {
        try {
            $update_name = $this->db->prepare("UPDATE tbl_admin SET name = :name WHERE user_id = :id ");
            $update_name->bindParam(':name', $name);
            $update_name->bindParam(':id', $id);
            $update_name->execute();
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public function update_staff_username($username, $id)
    {
        try {
            $update_username = $this->db->prepare("UPDATE tbl_admin SET username = :username WHERE user_id = :id ");
            $update_username->bindParam(':username', $username);
            $update_username->bindParam(':id', $id);
            $update_username->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function add_new_task($data)
    {
        $task_title  = $this->test_form_input_data($data['task_title']);
        $task_description = $this->test_form_input_data($data['task_description']);
        if (isset($data['task_start_time'])) {
            $task_start_time = $this->test_form_input_data($data['task_start_time']);
        } else {
            $task_start_time = null;
        }
        $task_end_time = $this->test_form_input_data($data['task_end_time']);
        $task_assign_to =  $this->test_form_input_data($data['t_user_id']);

        try {
            $add_task = $this->db->prepare("INSERT INTO task_info (task_title, task_description, task_start_time, task_end_time, t_user_id) VALUES (:x, :y, :z, :a, :b) ");

            $add_task->bindParam(':x', $task_title);
            $add_task->bindParam(':y', $task_description);
            $add_task->bindParam(':z', $task_start_time);
            $add_task->bindParam(':a', $task_end_time);
            $add_task->bindParam(':b', $task_assign_to);

            $add_task->execute();

            header('Location: task-info.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function update_task_info($data, $task_id)
{
    $task_title = $this->test_form_input_data($data['task_title']);
    $task_description = $this->test_form_input_data($data['task_description']);

    // Validate and convert input date and time strings
    $task_start_time_str = $data['task_start_time'];
    $task_end_time_str = $data['task_end_time'];

    // Convert input strings to the expected format (Y-m-d H:i:s)
    $task_start_time_str = date('Y-m-d H:i:s', strtotime($task_start_time_str));
    $task_end_time_str = date('Y-m-d H:i:s', strtotime($task_end_time_str));

    // Bind the converted date and time strings to the prepared statement
    $task_assign_to = $this->test_form_input_data($data['t_user_id']);
    $task_status = $this->test_form_input_data($data['task_status']);
    $task_grade = isset($data['task_grade']) ? $this->test_form_input_data($data['task_grade']): null;

    try {
        $update_task = $this->db->prepare("UPDATE task_info SET 
            task_title = :x, 
            task_description = :y, 
            task_start_time = :z, 
            task_end_time = :a, 
            task_assign_to = :b, 
            task_status = :c,
            task_grade = :d
            WHERE task_id = :id ");

        $update_task->bindParam(':x', $task_title);
        $update_task->bindParam(':y', $task_description);
        $update_task->bindParam(':z', $task_start_time_str); // Bind converted string
        $update_task->bindParam(':a', $task_end_time_str); // Bind converted string
        $update_task->bindParam(':b', $task_assign_to);
        $update_task->bindParam(':c', $task_status);
        $update_task->bindParam(':d', $task_grade);
        $update_task->bindParam(':id', $task_id);

        $update_task->execute();

        header('Location: task-info.php');
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

    public function delete_task_info($task_id)
    {
        try {
            $delete_task = $this->db->prepare("DELETE FROM task_info WHERE task_id = :id ");
            $delete_task->bindParam(':id', $task_id);
            $delete_task->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_all_task()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM task_info");
            $stmt->execute();
            $task = $stmt->fetchAll();
            return $task;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_single_task($task_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM task_info WHERE task_id = :id ");
            $stmt->bindParam(':id', $task_id);
            $stmt->execute();
            $task = $stmt->fetch();
            return $task;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function add_punch_in($data)
    {
        $date = new DateTime('now', new DateTimeZone('Asia/Manila'));

        $user_id  = $this->test_form_input_data($data['user_id']);
        $punch_in_time = $date->format('Y-m-d H:i:s');

        try {
            $add_attendance = $this->db->prepare("INSERT INTO attendance_info (attendance_id, in_time) VALUES (:x, :y) ");
            $add_attendance->bindParam(':x', $user_id);
            $add_attendance->bindParam(':y', $punch_in_time);
            $add_attendance->execute();

            header('Location: attendance-info.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function add_punch_out($data)
    {
        $date = new DateTime('now', new DateTimeZone('Asia/Manila'));
        $out_time = $date->format('Y-m-d H:i:s');
        $in_time  = $data['in_time'] ?? null; // Debugging the issue here

        $dteStart = new DateTime($in_time);
        $dteEnd   = new DateTime($out_time);
        $dteDiff  = $dteStart->diff($dteEnd);
        $total_duration = $dteDiff->format("%H:%I:%S");

        $attendance_id  = $this->test_form_input_data($data['attendance_id']);

        try {
            $update_attendance = $this->db->prepare("UPDATE attendance_info SET out_time = :x, total_duration = :y WHERE attendance_id = :id ");
            $update_attendance->bindParam(':x', $out_time);
            $update_attendance->bindParam(':y', $total_duration);
            $update_attendance->bindParam(':id', $attendance_id);
            $update_attendance->execute();

            header('Location: attendance-info.php');
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function delete_attendance_info($attendance_id)
    {
        try {
            $delete_attendance = $this->db->prepare("DELETE FROM attendance_info WHERE attendance_id = :id ");
            $delete_attendance->bindParam(':id', $attendance_id);
            $delete_attendance->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function fetch_all_attendance($attendance_id)
{
    $pdo = $this->pdo;

        try {
            $stmt = $this->db->prepare("SELECT * FROM attendance_info");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
}

    public function fetch_single_attendance($attendance_id)
    {
       try {
            $stmt = $this->db->prepare("SELECT * FROM attendance_info WHERE attendance_id = :id ");
            $stmt->bindParam(':id', $attendance_id);
            $stmt->execute();
            $attendance = $stmt->fetch();
            return $attendance;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}