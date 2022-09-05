<?php

require_once('../../../private/initialize.php');

require_login();

$class_set = find_all_class();

?>

<?php $page_title = 'View all classes'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Classes</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/class/new.php'); ?>">Create New Class</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/class/search.php'); ?>">Search Class by Name</a>
        </div>

        <table class="list">
            <tr>
                <th>Class Name</th>
                <th>Rental Rate</th>
                <th>Overtime Fee</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($class = mysqli_fetch_assoc($class_set)) { ?>
                <tr>
                    <td><?php echo h($class['class_name']); ?></td>
                    <td><?php echo h($class['rental_rate']); ?></td>
                    <td><?php echo h($class['over_fee']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/class/edit.php?c_id=' . h(u($class['c_id']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/class/delete.php?c_id=' . h(u($class['c_id']))); ?>">Delete</a></td>
                </tr>
            <?php }mysqli_free_result($class_set); ?>
        </table>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
