<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $location = [];
    $location['l_street'] = $_POST['l_street'] ?? '';
    $location['l_city'] = $_POST['l_city'] ?? '';
    $location['l_state'] = $_POST['l_state'] ?? '';
    $location['l_zipcode'] = $_POST['l_zipcode'] ?? '';
    $location['l_pno'] = $_POST['l_pno'] ?? '';

    $result = insert_location($location);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The location was created successfully.';
        redirect_to(url_for('/staff/location/show.php?l_id=' . $new_id));
    } else {
        $errors = $result;
    }

} else {

    $location = [];
    $location['l_street'] = '';
    $location['l_city'] = '';
    $location['l_state'] = '';
    $location['l_zipcode'] = '';
    $location['l_pno'] = '';

}

?>

<?php $page_title = 'Create Location'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/location/index.php'); ?>">&laquo; Back to Location Page</a>

    <div class="class new">
        <h1>Create Location</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/location/new.php'); ?>" method="post">
            <dl>
                <dt>Street</dt>
                <dd><input type="text" name="l_street" value="<?php echo h($location['l_street']); ?>" /></dd>
            </dl>
            <dl>
                <dt>City</dt>
                <dd><input type="text" name="l_city" value="<?php echo h($location['l_city']); ?>" /></dd>
            </dl>
            <dl>
                <dt>State</dt>
                <dd><input type="text" name="l_state" value="<?php echo h($location['l_state']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Zipcode</dt>
                <dd><input type="text" name="l_zipcode" value="<?php echo h($location['l_zipcode']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Phone Number</dt>
                <dd><input type="text" name="l_pno" value="<?php echo h($location['l_pno']); ?>" /></dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Location" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
