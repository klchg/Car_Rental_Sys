<?php

require_once('../../../private/initialize.php');

require_login();

$emp_set = find_all_emp();

?>

<?php $page_title = 'View all corporate customers'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Corporate Customers</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/emp/new.php'); ?>">Create New Corporate Customer</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/emp/search.php'); ?>">Search Corporate Customers</a>
        </div>

        <table class="list">
            <tr>
                <th>Corporate Customer ID</th>
                <th>Corporation Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($emp = mysqli_fetch_assoc($emp_set)) { ?>
                <tr>
                    <td><?php echo h($emp['emp_id']); ?></td>
                    <td><?php echo h($emp['c_name']); ?></td>
                    <td><?php echo h($emp['c_email']); ?></td>
                    <td><?php echo h($emp['p_no']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/emp/show.php?c_no=' . h(u($emp['c_no']))); ?>">View</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/emp/edit.php?c_no=' . h(u($emp['c_no']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/emp/delete.php?c_no=' . h(u($emp['c_no']))); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($emp_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
