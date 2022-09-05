<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['cou_id'])) {
    redirect_to(url_for('/staff/coupon/index.php'));
}
$cou_id = $_GET['cou_id'];

if(is_post_request()) {

    // Handle form values sent by new.php
    $coupon = [];
    $coupon['cou_id'] = $cou_id;
    $coupon['cou_discount'] = $_POST['cou_discount'] ?? '';
    $coupon['s_date'] = $_POST['s_date'] ?? '';
    $coupon['e_date'] = $_POST['e_date'] ?? '';
    $coupon['cou_no'] = $_POST['cou_no'] ?? '';
    $coupon['is_available'] = $_POST['is_available'] ?? '';

    $result = update_coupon($coupon);
    if($result === true) {
        $_SESSION['message'] = 'The coupon was updated successfully.';
        redirect_to(url_for('/staff/coupon/show.php?cou_id=' . $cou_id));
    } else {
        $errors = $result;
    }

} else {

    $coupon = find_coupon_by_id($cou_id);

}

?>

<?php $page_title = 'Edit Coupon'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coupon/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="coupon edit">
        <h1>Edit Coupon</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/coupon/edit.php?cou_id=' . h(u($cou_id))); ?>" method="post">

                <input type="hidden" name="cou_id" value="<?php echo h($coupon['cou_id']);?>"/>
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
<!--                <dl>-->
<!--                    <dt>Coupon Number</dt>-->
<!--                    <dd>-->
<!--                        <input name="cou_no" type="text" value="--><?php //echo h($coupon['cou_no']); ?><!--"/>-->
<!--                    </dd>-->
<!--                </dl>-->
                <dl>
                    <dt>Availability</dt>
                    <dd>
                        <select name="is_available">
                            <?php
                                echo "<option value=\"1\"";
                                if($coupon['is_available'] === true) {
                                    echo " selected";
                                }
                                echo ">Yes</option>";
                                echo "<option value=\"0\"";
                                if($coupon['is_available'] === false) {
                                    echo " selected";
                                }
                                echo ">No</option>";

                            ?>
                        </select>
                    </dd>
                </dl>
                <div id="operations">
                    <input type="submit" value="Edit Coupon" />
                </div>

        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
