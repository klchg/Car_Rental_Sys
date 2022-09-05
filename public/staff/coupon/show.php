<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$cou_id = $_GET['cou_id']; // PHP > 7.0

$coupon = find_coupon_by_id($cou_id);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coupon/index.php'); ?>">&laquo; Back to Coupon Page</a>

    <div class="coupon show">

        <h1>Details </h1>
            <div class="attributes">
                <dl>
                    <dt>Coupon Discount</dt>
                    <dd><?php echo h($coupon['cou_discount']); ?></dd>
                </dl>
                <dl>
                    <dt>Coupon Start Date</dt>
                    <dd><?php echo substr(h($coupon['s_date']), 0, 10); ?></dd>
                </dl>
                <dl>
                    <dt>Coupon End Date</dt>
                    <dd><?php echo substr(h($coupon['e_date']), 0, 10); ?></dd>
                </dl>
                <dl>
                    <dt>Coupon Number</dt>
                    <dd><?php echo h($coupon['cou_no']); ?></dd>
                </dl>
            </div>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
