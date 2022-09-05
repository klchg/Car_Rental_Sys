<?php

require_once('../../../private/initialize.php');

require_login();

$order_set = find_all_orders();
$going_set = [];
$unstarted_orders =[];


?>

<?php $page_title = 'View all services'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Services</h1>
        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/service/search.php'); ?>">Search Order</a>
        </div>

    <h4>Completed Orders</h4>
    <?php if(mysqli_num_rows($order_set)>0){?>
        <table class="list">
            <tr>
                <th>Pick up Date</th>
                <th>Drop Date</th>
                <th>Daily Limit</th>
                <th>Username</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
            while($order = mysqli_fetch_assoc($order_set)){
                if (!isset($order['is_complete']) || $order['is_complete'] == 0) {
                    array_push($going_set, $order);
                } else {
                    ?>

                    <tr>
                        <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                        <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                        <td><?php echo h($order['daily_limit']); ?></td>
                        <td><?php echo h($order['username']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/service/show_payment.php?&s_id=' . h(u($order['s_id'])));?>">Payments</a></td>
                    </tr>
                <?php }} ?>
        </table>
        <?php mysqli_free_result($order_set); }
    else{?>
        <p>No Orders</p>
    <?php }?>

    <h4>Going Orders</h4>
    <?php if($going_set!=[]){?>
        <table class="list">
            <tr>
                <th>Pick up Date</th>
                <th>Drop Date</th>
                <th>Daily Limit</th>
                <th>Username</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
            foreach($going_set as $key => $order){
                if (!isset($order['pk_date']) || empty($order['pk_date']) ||$order['pk_date'] == '' || strtotime($order['pk_date']) > time()) {
                    array_push($unstarted_orders, $order);
                } else {
                    ?>

                    <tr>
                        <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                        <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                        <td><?php echo h($order['daily_limit']); ?></td>
                        <td><?php echo h($order['username']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
<!--                        <td><a class="action" href="--><?php //echo url_for('/staff/service/edit.php?&s_id=' . h(u($order['s_id'])));?><!--">Edit&nbsp;</a></td>-->
                    </tr>
                <?php }} ?>
        </table>
    <?php }
    else{?>
        <p>No Orders</p>
    <?php }?>

    <h4>Unstarted Orders</h4>
    <?php if($unstarted_orders!=[]){?>
        <table class="list">
            <tr>
                <th>Pick up Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Drop Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>Daily Limit &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                <th>Username &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
            foreach($unstarted_orders as $key => $order){ ?>

                <tr>
                    <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                    <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                    <td><?php echo h($order['daily_limit']); ?></td>
                    <td><?php echo h($order['username']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
<!--                    <td><a class="action" href="--><?php //echo url_for('/staff/service/edit.php?&s_id=' . h(u($order['s_id'])));?><!--">Edit&nbsp;</a></td>-->
                    <td><a class="action" href="<?php echo url_for('/staff/service/delete.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Delete</a></td>
                </tr>
            <?php } ?>
        </table>
    <?php }
    else{?>
        <p>No Orders</p>
    <?php }?>
        </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
