<?php

require_once('../../../private/initialize.php');

require_login();

$vehicle_set = find_all_vehicle();

?>

<?php $page_title = 'View all vehicles'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Vehicle</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/vehicle/new.php'); ?>">Create New Vehicle</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/vehicle/search.php'); ?>">Search Vehicle</a>
        </div>

        <table class="list">
            <tr>
                <th>Class</th>
                <th>Brand</th>
                <th>Rental Rate</th>
                <th>Over Limit Fee</th>
                <th>Made Year</th>
                <th>VIN</th>
<!--                <th>Model number</th>-->
<!--                <th>License Plate Number</th>-->
<!--                <th>Location Street</th>-->
<!--                <th>Location City</th>-->
<!--                <th>Location State</th>-->
<!--                <th>Location Zipcode</th>-->
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($vehicle = mysqli_fetch_assoc($vehicle_set)) { ?>
                <tr>
                    <td><?php echo h($vehicle['make']); ?></td>
                    <td><?php echo h($vehicle['class_name']); ?></td>
                    <td><?php echo h($vehicle['rental_rate']); ?></td>
                    <td><?php echo h($vehicle['over_fee']); ?></td>
                    <td><?php echo h($vehicle['year']); ?></td>
                    <td><?php echo h($vehicle['vin']); ?></td>
<!--                    <td>--><?php //echo h($vehicle['model']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($vehicle['lic_p_no']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($vehicle['l_street']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($vehicle['l_city']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($vehicle['l_state']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($vehicle['l_zipcode']); ?><!--</td>-->
                    <td><a class="action" href="<?php echo url_for('/staff/vehicle/show.php?vid=' . h(u($vehicle['vid']))); ?>">View</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/vehicle/edit.php?vid=' . h(u($vehicle['vid']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/vehicle/delete.php?vid=' . h(u($vehicle['vid']))); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($vehicle_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
