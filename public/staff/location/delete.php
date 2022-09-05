<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['l_id'])) {
    redirect_to(url_for('/staff/location/index.php'));
}
$l_id = $_GET['l_id'];

$location_set = find_location_by_id($l_id);

if(is_post_request()) {

    $result = delete_location($l_id);
    $_SESSION['message'] = 'The location was deleted successfully.';
    redirect_to(url_for('/staff/location/index.php'));

}

?>

<?php $page_title = 'Delete Location'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/location/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="location delete">
        <?php while($location = mysqli_fetch_assoc($location_set)){ ?>
            <h1>Delete Location</h1>
            <p>Are you sure you want to delete this location?</p>
            <p class="item">Street: <?php echo h($location['l_street']); ?></p>
            <p class="item">City: <?php echo h($location['l_city']); ?></p>
            <p class="item">State: <?php echo h($location['l_state']); ?></p>
            <p class="item">Zipcode: <?php echo h($location['l_zipcode']); ?></p>
            <p class="item">Phone Number: <?php echo h($location['l_pno']); ?></p>

            <form action="<?php echo url_for('/staff/location/delete.php?l_id=' . h(u($location['l_id']))); ?>" method="post">
                <div id="operations">
                    <input type="submit" name="commit" value="Delete Location" />
                </div>
            </form>
        <?php } mysqli_free_result($location_set); ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
