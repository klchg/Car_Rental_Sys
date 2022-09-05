<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['l_id'])) {
    redirect_to(url_for('/staff/location/index.php'));
}
$l_id = $_GET['l_id'];

if(is_post_request()) {

    $location = [];
    $location['l_id'] = $l_id;
    $location['l_street'] = $_POST['l_street'] ?? '';
    $location['l_city'] = $_POST['l_city'] ?? '';
    $location['l_state'] = $_POST['l_state'] ?? '';
    $location['l_zipcode'] = $_POST['l_zipcode'] ?? '';
    $location['l_pno'] = $_POST['l_pno'] ?? '';

    $result = update_location($location);
    if($result === true) {
        $_SESSION['message'] = 'The location was updated successfully.';
        redirect_to(url_for('/staff/location/show.php?l_id=' . $l_id));
    } else {
        $errors = $result;
    }

} else {

    $location_set = find_location_by_id($l_id);

}

?>

<?php $page_title = 'Edit Location'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/location/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="location edit">
        <h1>Edit Location</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/location/edit.php?l_id=' . h(u($l_id))); ?>" method="post">
            <?php while($location = mysqli_fetch_assoc($location_set)){ ?>
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
                <input type="submit" value="Edit Location" />
            </div>
            <?php } mysqli_free_result($location_set); ?>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
