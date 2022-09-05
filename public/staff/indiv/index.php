<?php

require_once('../../../private/initialize.php');

require_login();

$indiv_set = find_all_indiv();

?>

<?php $page_title = 'View all individual customers'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Individual Customers</h1>

<!--        <div class="actions">-->
<!--            <a class="action" href="--><?php //echo url_for('/staff/indiv/new.php'); ?><!--">Create New Corporation Record</a>-->
<!--        </div>-->

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/indiv/search.php'); ?>">Search Individual Customers</a>
        </div>

        <table class="list">
            <tr>
                <th>Customer Firstname</th>
                <th>Customer Lastname</th>
                <th>Driver License Number</th>
                <th>Insurance Company Name</th>
                <th>Insurance Policy Number</th>
                <th>&nbsp;</th>
<!--                <th>&nbsp;</th>-->
            </tr>

            <?php while($indiv = mysqli_fetch_assoc($indiv_set)) { ?>
                <tr>
                    <td><?php echo h($indiv['i_fname']); ?></td>
                    <td><?php echo h($indiv['i_lname']); ?></td>
                    <td><?php echo h($indiv['dl_no']); ?></td>
                    <td><?php echo h($indiv['ins_c_name']); ?></td>
                    <td><?php echo h($indiv['ins_p_no']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/indiv/show.php?c_no=' . h(u($indiv['c_no']))); ?>">View</a></td>
<!--                    <td><a class="action" href="--><?php //echo url_for('/staff/indiv/delete.php?c_no=' . h(u($indiv['c_no']))); ?><!--">Delete</a></td>-->
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($indiv_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
