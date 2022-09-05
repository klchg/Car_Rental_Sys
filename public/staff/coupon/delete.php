<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['cou_id'])) {
    redirect_to(url_for('/staff/coupon/index.php'));
}
$cou_id = $_GET['cou_id'];

$coupon = find_coupon_by_id($cou_id);

if(is_post_request()) {

    $result = delete_coupon($cou_id);
    $_SESSION['message'] = 'The coupon was deleted successfully.';
    redirect_to(url_for('/staff/coupon/index.php'));

}

?>

<?php $page_title = 'Delete Coupon'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coupon/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class delete">
        <h1>Delete Class</h1>
        <p>Are you sure you want to delete this class?</p>
            <p class="item">Coupon Discount: <?php echo h($coupon['cou_discount']); ?></p>
            <p class="item">Coupon Start Date: <?php echo substr(h($coupon['s_date']), 0, 10); ?></p>
            <p class="item">Coupon End Date: <?php echo substr(h($coupon['e_date']), 0, 10); ?></p>
            <p class="item">Coupon Number: <?php echo h($coupon['cou_no']); ?></p>
            <p class="item">Availability: <?php echo h($coupon['is_available']); ?></p>

        <form action="<?php echo url_for('/staff/coupon/delete.php?cou_id=' . h(u($coupon['cou_id']))); ?>" method="post">
            <div id="operations">
                <input type="submit" name="commit" value="Delete Coupon" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
