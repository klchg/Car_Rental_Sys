<?php

require_once('../../../private/initialize.php');

require_login();

$coop_set = find_all_coop();

?>

<?php $page_title = 'View all corporations'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Corporations</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/coop/new.php'); ?>">Create New Corporation Record</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/coop/search.php'); ?>">Search Corporation</a>
        </div>

        <table class="list">
            <tr>
                <th>Corporation Name</th>
                <th>Registration Number</th>
                <th>Discount Rate</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($coop = mysqli_fetch_assoc($coop_set)) { ?>
                <tr>
                    <td><?php echo h($coop['c_name']); ?></td>
                    <td><?php echo h($coop['reg_no']); ?></td>
                    <td><?php echo h($coop['c_rate']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/coop/edit.php?coop_id=' . h(u($coop['coop_id']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/coop/delete.php?coop_id=' . h(u($coop['coop_id']))); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($coop_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
