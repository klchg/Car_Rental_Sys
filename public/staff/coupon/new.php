<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $coupon = [];
    $coupon['cou_discount'] = $_POST['cou_discount'] ?? '';
    $coupon['s_date'] = $_POST['s_date'] ?? '';
    $coupon['e_date'] = $_POST['e_date'] ?? '';

    $result = insert_coupon($coupon);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The coupon was created successfully.';
        redirect_to(url_for('/staff/coupon/show.php?cou_id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {

    $coupon = [];
    $coupon['cou_discount'] = '';
    $coupon['s_date'] = '';
    $coupon['e_date'] = '';

}

?>

<?php $page_title = 'Create Coupon'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coupon/index.php'); ?>">&laquo; Back to Coupon Page</a>

    <div class="coupon new">
        <h1>Create Coupon</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/coupon/new.php'); ?>" method="post">
            <dl>
                <dt>Coupon Discount</dt>
                <dd><input type="text" name="cou_discount" value="<?php echo h($coupon['cou_discount']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Coupon Start Date</dt>
                <dd><input type="date" name="s_date" value="<?php echo substr(h($coupon['s_date']), 0, 10); ?>" /></dd>
            </dl>
            <dl>
                <dt>Coupon End Date</dt>
                <dd><input type="date" name="e_date" value="<?php echo substr(h($coupon['e_date']), 0, 10); ?>" /></dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Coupon" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
