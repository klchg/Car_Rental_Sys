<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $coop = [];
    $coop['c_name'] = $_POST['c_name'] ?? '';
    $coop['reg_no'] = $_POST['reg_no'] ?? '';
    $coop['c_rate'] = $_POST['c_rate'] ?? '';

    $result = insert_coop($coop);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The corporation record was created successfully.';
        redirect_to(url_for('/staff/coop/show.php?coop_id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {

    $coop = [];
    $coop['c_name'] = '';
    $coop['reg_no'] = '';
    $coop['c_rate'] = '';

}

?>

<?php $page_title = 'Create Corporation Record'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coop/index.php'); ?>">&laquo; Back to Corporation Page</a>

    <div class="coupon new">
        <h1>Create Corporation Record</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/coop/new.php'); ?>" method="post">
            <dl>
                <dt>Corporation Name</dt>
                <dd><input type="text" name="c_name" value="<?php echo h($coop['c_name']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Registration Number</dt>
                <dd><input type="text" name="reg_no" value="<?php echo h($coop['reg_no']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Discount Rate</dt>
                <dd><input type="text" name="c_rate" value="<?php echo h($coop['c_rate']); ?>" /></dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Corporation Record" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
