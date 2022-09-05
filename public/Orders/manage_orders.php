<?php

require_once('../../private/initialize.php');

require_login_customer();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$order_set = [];
$customer_id = -1;
$service_id = -1;

if (isset($_GET['c_no']) && !empty($_GET['c_no']) && $_GET['c_no'] != '') {
    $customer_id = $_GET['c_no'];
}
if (isset($_GET['s_id']) && !empty($_GET['s_id']) && $_GET['s_id'] != '') {
    $service_id = $_GET['s_id'];
}

if (-1 == $customer_id && -1 == $service_id) {
    redirect_to(url_for('/Orders/myorders.php'));
} else if (-1==$service_id) {
    $order_set = find_orders_by_customer_id($customer_id);
} else if (-1==$customer_id) {
    $order_set = find_orders_by_service_id($service_id);
} else {
    $order_set = find_orders_by_customer_id_and_service_id($customer_id, $service_id);
}



//$id = $_GET['id'] ?? '1'; // PHP > 7.0
//
//$order = find_order_by_id($id);
//$subject = find_subject_by_id($page['subject_id']);

?>

<?php $page_title = 'My Orders'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

    <div id="content">
        <a class="back-link" href="<?php echo url_for('pagetwo.php'); ?>">&laquo; Back to My Home Page</a>

        <div class="orders show">
            <h1>Show all my orders</h1>

            <?php if($order_set!=[]){?>
                <table class="list">
                    <tr>
                        <th>Brand</th>
                        <th>Pick up Date</th>
                        <th>Pick up Location</th>
                        <th>Drop Date</th>
                        <th>Drop Location</th>
                        <th>Daily Limit</th>
                        <th>Rental Rate</th>
                        <th>Over Fee</th>
                        <th>Coupon Discount</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php
                    while($order = mysqli_fetch_assoc($order_set)){ ?>

                        <tr>
                            <td><?php echo h($order['make']); ?></td>
                            <td><?php echo h($order['pk_date']); ?></td>
                            <td><?php echo h($order['pk_city']); ?></td>
                            <td><?php echo h($order['d_date']); ?></td>
                            <td><?php echo h($order['d_city']); ?></td>
                            <td><?php echo h($order['daily_limit']); ?></td>
                            <td><?php echo h($order['rental_rate']); ?></td>
                            <td><?php echo h($order['over_fee']); ?></td>
                            <td><?php echo h($order['cou_discount']); ?></td>
                            <td><a class="action" href="<?php echo url_for('/Orders/edit.php?&s_id=' . h(u($order['s_id'])) . '&pk_date='. h(u($order['pk_date'])) . '&pk_street='. h(u($order['pk_street'])) . '&pk_city='. h(u($order['pk_city'])) . '&pk_state='. h(u($order['pk_state'])));?>">Edit</a></td>
                            <td><a class="action" href="<?php echo url_for('/Orders/pay_order.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>">Pay</a></td>
                            <td><a class="action" href="<?php echo url_for('/Orders/delete_order.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>">Delete</a></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php mysqli_free_result($order_set); }
            else{?>
                <p>no orders</p>
            <?php }?>



        </div>

    </div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>