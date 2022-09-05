<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$vid = $_GET['vid']; // PHP > 7.0

$vehicle_set = find_vehicle_by_id($vid);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/vehicle/index.php'); ?>">&laquo; Back to Vehicle Page</a>

    <div class="class show">

        <h1>Details </h1>
        <?php while($vehicle = mysqli_fetch_assoc($vehicle_set)){ ?>
        <div class="attributes">
            <dl>
                <dt>Class</dt>
                <dd><?php echo h($vehicle['class_name']); ?></dd>
            </dl>
            <dl>
                <dt>Brand</dt>
                <dd><?php echo h($vehicle['make']); ?></dd>
            </dl>
            <dl>
                <dt>Rental Rate</dt>
                <dd><?php echo h($vehicle['rental_rate']); ?></dd>
            </dl>
            <dl>
                <dt>Over Limit Fee</dt>
                <dd><?php echo h($vehicle['over_fee']); ?></dd>
            </dl>
            <dl>
                <dt>Made Year</dt>
                <dd><?php echo h($vehicle['year']); ?></dd>
            </dl>
            <dl>
                <dt>VIN</dt>
                <dd><?php echo h($vehicle['vin']); ?></dd>
            </dl>
            <dl>
                <dt>Model number</dt>
                <dd><?php echo h($vehicle['model']); ?></dd>
            </dl>
            <dl>
                <dt>License Plate Number</dt>
                <dd><?php echo h($vehicle['lic_p_no']); ?></dd>
            </dl>
            <dl>
                <dt>Location Street</dt>
                <dd><?php echo h($vehicle['l_street']); ?></dd>
            </dl>
            <dl>
                <dt>Location City</dt>
                <dd><?php echo h($vehicle['l_city']); ?></dd>
            </dl>
            <dl>
                <dt>Location State</dt>
                <dd><?php echo h($vehicle['l_state']); ?></dd>
            </dl>
            <dl>
                <dt>Location Zipcode</dt>
                <dd><?php echo h($vehicle['l_zipcode']); ?></dd>
            </dl>
        </div>
        <?php } mysqli_free_result($vehicle_set); ?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
