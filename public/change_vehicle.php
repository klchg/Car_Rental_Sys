<?php
require_once('../private/initialize.php');

require_login_customer();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
//$customer_id = $_SESSION['customer_id'];
$user_id = $_SESSION['customer_id'];
$customer = find_customer_id_by_user_id($user_id);
$order_set = find_orders_by_customer_id($customer['c_no']);

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
                            <td><a class="action" href="<?php echo url_for('edit.php?&s_id=' . h(u($order['s_id'])));?>">Edit Order</a></td>
                            <td><a class="action" href="<?php echo url_for('pay.php?&s_id=' . h(u($order['s_id'])));?>">Pay</a></td>
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