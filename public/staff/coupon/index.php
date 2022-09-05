<?php

require_once('../../../private/initialize.php');

require_login();

$coupon_set = find_all_coupon();

?>

<?php $page_title = 'View all coupons'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Coupons</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/coupon/new.php'); ?>">Create New Coupon</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/coupon/search.php'); ?>">Search Coupon</a>
        </div>

        <table class="list">
            <tr>
                <th>Coupon Discount</th>
                <th>Coupon Start Date</th>
                <th>Coupon End Date</th>
                <th>Coupon Number</th>
                <th>Availability</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($coupon = mysqli_fetch_assoc($coupon_set)) { ?>
                <tr>
                    <td><?php echo h($coupon['cou_discount']); ?></td>
                    <td><?php echo substr(h($coupon['s_date']), 0, 10); ?></td>
                    <td><?php echo substr(h($coupon['e_date']), 0, 10); ?></td>
                    <td><?php echo h($coupon['cou_no']); ?></td>
                    <td><?php echo h($coupon['is_available']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/coupon/edit.php?cou_id=' . h(u($coupon['cou_id']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/coupon/delete.php?cou_id=' . h(u($coupon['cou_id']))); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($coupon_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
