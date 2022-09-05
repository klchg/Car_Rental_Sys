<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['coop_id'])) {
    redirect_to(url_for('/staff/coop/index.php'));
}
$coop_id = $_GET['coop_id'];

if(is_post_request()) {

    // Handle form values sent by new.php
    $coop = [];
    $coop['coop_id'] = $coop_id;
    $coop['c_name'] = $_POST['c_name'] ?? '';
    $coop['reg_no'] = $_POST['reg_no'] ?? '';
    $coop['c_rate'] = $_POST['c_rate'] ?? '';

    $result = update_coop($coop);
    if($result === true) {
        $_SESSION['message'] = 'The corporation record was updated successfully.';
        redirect_to(url_for('/staff/coop/show.php?coop_id=' . $coop_id));
    } else {
        $errors = $result;
    }

} else {

    $coop_set = find_coop_by_id($coop_id);

}

?>

<?php $page_title = 'Edit Corporation Record'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coop/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="corporation edit">
        <h1>Edit Corporation Record</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/coop/edit.php?coop_id=' . h(u($coop_id))); ?>" method="post">

            <?php while($coop = mysqli_fetch_assoc($coop_set)){ ?>
                <input type="hidden" name="coop_id" value="<?php echo h($coop['coop_id'])?>"/>
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
                    <input type="submit" value="Edit Corporation Record" />
                </div>
            <?php } mysqli_free_result($coop_set); ?>

        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
