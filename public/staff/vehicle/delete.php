<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['vid'])) {
    redirect_to(url_for('/staff/vehicle/index.php'));
}
$vid = $_GET['vid'];

$vehicle_set = find_vehicle_by_id($vid);

if(is_post_request()) {

    $result = delete_vehicle($vid);
    $_SESSION['message'] = 'The vehicle was deleted successfully.';
    redirect_to(url_for('/staff/vehicle/index.php'));

}

?>

<?php $page_title = 'Delete Vehicle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/vehicle/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class delete">
        <h1>Delete Vehicle</h1>
        <p>Are you sure you want to delete this vehicle?</p>
        <?php while($vehicle = mysqli_fetch_assoc($vehicle_set)){ ?>
            <p class="item">Class: <?php echo h($vehicle['class_name']); ?></p>
            <p class="item">Brand: <?php echo h($vehicle['make']); ?></p>
            <p class="item">Rental Rate: <?php echo h($vehicle['rental_rate']); ?></p>
            <p class="item">Over Limit Fee: <?php echo h($vehicle['over_fee']); ?></p>
            <p class="item">Made Year: <?php echo h($vehicle['year']); ?></p>
            <p class="item">VIN: <?php echo h($vehicle['vin']); ?></p>
            <p class="item">Model number: <?php echo h($vehicle['model']); ?></p>
            <p class="item">License Plate Number: <?php echo h($vehicle['lic_p_no']); ?></p>
            <p class="item">Location Street: <?php echo h($vehicle['l_street']); ?></p>
            <p class="item">Location City: <?php echo h($vehicle['l_city']); ?></p>
            <p class="item">Location State: <?php echo h($vehicle['l_state']); ?></p>
            <p class="item">Location Zipcode: <?php echo h($vehicle['l_zipcode']); ?></p>

            <form action="<?php echo url_for('/staff/vehicle/delete.php?vid=' . h(u($vehicle['vid']))); ?>" method="post">
                <div id="operations">
                    <input type="submit" name="commit" value="Delete Vehicle" />
                </div>
            </form>
        <?php } mysqli_free_result($vehicle_set); ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
