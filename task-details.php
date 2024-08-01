<?php 
    require 'classes/admin_class.php';
    $obj_admin = new Admin_Class();
    $task_id = $_GET['task_id'];
    $task_info = $obj_admin->update_task_info($task_id, $updated_data);
    $row = $task_info->fetch(PDO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="well rounded-0">
            <h3 class="text-center bg-primary" style="padding: 7px;">Task Details </h3><br>
            <div class="form-group">
                <label class="control-label text-p-reset">Task Title</label>
                <div class="">
                    <input type="text" readonly class="form-control rounded-0" value="<?php echo $row['task_title']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-p-reset">Task Description</label>
                <div class="">
                    <textarea name="task_description" readonly class="form-control rounded-0" rows="5" cols="5"><?php echo $row['task_description']; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-p-reset">Start Time</label>
                <div class="">
                    <input type="text" readonly class="form-control rounded-0" value="<?php echo $row['task_start_time']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-p-reset">End Time</label>
                <div class="">
                    <input type="text" readonly class="form-control rounded-0" value="<?php echo $row['task_end_time']; ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label text-p-reset">Assign To</label>
                <div class="">
                    <?php 
                        $sql = "SELECT user_id, fullname FROM tbl_admin WHERE user_role = 2";
                        $info = $obj_admin->manage_all_info($sql);   
                    ?>
                    <select class="form-control rounded-0" name="assign_to" readonly>
                        <option value="">Select Employee...</option>

                        <?php while($row = $info->fetch(PDO::FETCH_ASSOC)){ ?>
                        <option value="<?php echo $row['user_id']; ?>" <?php if ($row['user_id'] == $row['task_assign_to']) { ?>selected <?php } ?>><?php echo $row['fullname']; ?></option>
                        <?php } ?>
                    </select>
                </div>
               
            </div>
            <div class="form-group">
                <label class="control-label text-p-reset">Status</label>
                <div class="">
                    <select class="form-control rounded-0" name="status" disabled>
                        <option value="0" <?php if ($row['task_status'] == 0) { ?>selected <?php } ?>>Incomplete</option>
                        <option value="1" <?php if ($row['task_status'] == 1) { ?>selected <?php } ?>>In Progress</option>
                        <option value="2" <?php if ($row['task_status'] == 2) { ?>selected <?php } ?>>Completed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
