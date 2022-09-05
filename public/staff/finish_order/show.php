<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$s_id = $_GET['s_id']; // PHP > 7.0
$order = find_order_by_id($s_id);
$going_set = [];
$unstarted_orders =[];
?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div class="orders show">

    <div class="actions">
        <a class="back-link" href="<?php echo url_for('/staff/finish_order/search.php'); ?>">&laquo; Back to Previous Page</a>
    </div>

    <?php
    if (!isset($order['is_complete']) || $order['is_complete'] == 0) {
            array_push($going_set, $order);
    } else { ?>

    <h4>Completed Orders</h4>
    <?php if($order!=[]){?>
        <table class="list">
            <tr>
                <th>Username</th>
                <th>Brand</th>
                <th>Pick up Date</th>
                <th>Pick up Loc</th>
                <th>Drop Date</th>
                <th>Drop Location</th>
                <th>Daily Limit</th>
                <th>Rental Rate</th>
                <th>Overlimit Fee</th>
                <th>Coupon Discount</th>
                <th>Total Fee</th>
            </tr>
            <tr>
                <td><?php echo h($order['username']); ?></td>
                <td><?php echo h($order['make']); ?></td>
                <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                <td><?php echo h($order['pk_city']); ?></td>
                <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                <td><?php echo h($order['d_city']); ?></td>
                <td><?php echo h($order['daily_limit']); ?></td>
                <td><?php echo h($order['rental_rate']); ?></td>
                <td><?php echo h($order['over_fee']); ?></td>
                <td><?php echo h($order['cou_discount']); ?></td>
                <td><?php echo h($order['i_amount']); ?></td>
            </tr>
                <?php }} ?>
        </table>
    <?php if($going_set!=[]){
            foreach($going_set as $key => $order){
                if (!isset($order['pk_date']) || empty($order['pk_date']) ||$order['pk_date'] == '' || strtotime($order['pk_date']) > time()) {
                    array_push($unstarted_orders, $order);
                } else { ?>
    <h4>Going Orders</h4>
        <table class="list">
            <tr>
                <th>Username</th>
                <th>Brand</th>
                <th>Pick up Date</th>
                <th>Pick up Loc</th>
                <th>Drop Date</th>
                <th>Drop Location</th>
                <th>Daily Limit</th>
                <th>Rental Rate</th>
                <th>Overlimit Fee</th>
                <th>Coupon Discount</th>
                <th>Total Fee</th>
            </tr>
            <tr>
                <td><?php echo h($order['username']); ?></td>
                <td><?php echo h($order['make']); ?></td>
                <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                <td><?php echo h($order['pk_city']); ?></td>
                <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                <td><?php echo h($order['d_city']); ?></td>
                <td><?php echo h($order['daily_limit']); ?></td>
                <td><?php echo h($order['rental_rate']); ?></td>
                <td><?php echo h($order['over_fee']); ?></td>
                <td><?php echo h($order['cou_discount']); ?></td>
                <td><?php echo h($order['i_amount']); ?></td>
            </tr>
        </table>
        <?php }}} ?>


    <?php if($unstarted_orders!=[]){
            foreach($unstarted_orders as $key => $order){ ?>
    <h4>Unstarted Orders</h4>
        <table class="list">
            <tr>
                <th>Username</th>
                <th>Brand</th>
                <th>Pick up Date</th>
                <th>Pick up Loc</th>
                <th>Drop Date</th>
                <th>Drop Location</th>
                <th>Daily Limit</th>
                <th>Rental Rate</th>
                <th>Overlimit Fee</th>
                <th>Coupon Discount</th>
                <th>Total Fee</th>
            </tr>
            <tr>
                <td><?php echo h($order['username']); ?></td>
                <td><?php echo h($order['make']); ?></td>
                <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                <td><?php echo h($order['pk_city']); ?></td>
                <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                <td><?php echo h($order['d_city']); ?></td>
                <td><?php echo h($order['daily_limit']); ?></td>
                <td><?php echo h($order['rental_rate']); ?></td>
                <td><?php echo h($order['over_fee']); ?></td>
                <td><?php echo h($order['cou_discount']); ?></td>
                <td><?php echo h($order['i_amount']); ?></td>
            </tr>
        </table>
        <?php }} ?>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
