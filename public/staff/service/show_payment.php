<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$s_id = $_GET['s_id']; // PHP > 7.0
//$order = find_order_by_id($s_id);
$payment_set = find_payment_by_service_id($s_id);
$total_amount = '';
$paid_sum = 0;
?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/service/index.php'); ?>">&laquo; Back to Service Page</a>

    <div class="orders show">

        <h4>Payments</h4>
        <?php if(mysqli_num_rows($payment_set)>0){?>
            <table class="list">
                <tr>
                    <th>Customer Number</th>
                    <th>Payment method</th>
                    <th>Payment date</th>
                    <th>Card Number</th>
                    <th>Payment Amount</th>
                </tr>
                <?php while($payment = mysqli_fetch_assoc($payment_set)){ ?>
                <tr>
                    <td><?php echo h($payment['c_no']); ?></td>
                    <td><?php echo h($payment['method']); ?></td>
                    <td><?php echo substr(h($payment['p_date']), 0, 10); ?></td>
                    <td><?php echo h($payment['card_no']); ?></td>
                    <td><?php echo h($payment['p_amount']); ?></td>
                </tr>
                <?php $total_amount=$payment['i_amount'];
                      $paid_sum += $payment['p_amount'];} ?>
            </table>
        <h5>Total Amount: $<?php echo h($total_amount); ?></h5>
        <h5>Remain Amount: $<?php echo h($total_amount-$paid_sum); ?></h5>
        <?php mysqli_free_result($payment_set);}?>

    </div>
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
