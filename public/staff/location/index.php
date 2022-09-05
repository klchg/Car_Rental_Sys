<?php

require_once('../../../private/initialize.php');

require_login();

$location_set = find_all_location();

?>

<?php $page_title = 'View all locations'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <div class="subjects listing">
        <h1>Locations</h1>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/location/new.php'); ?>">Create New Location</a>
        </div>

        <div class="actions">
            <a class="action" href="<?php echo url_for('/staff/location/search.php'); ?>">Search Location</a>
        </div>

        <table class="list">
            <tr>
                <th>Street</th>
                <th>City</th>
                <th>State</th>
                <th>Zipcode</th>
                <th>Phone Number</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php while($location = mysqli_fetch_assoc($location_set)) { ?>
                <tr>
                    <td><?php echo h($location['l_street']); ?></td>
                    <td><?php echo h($location['l_city']); ?></td>
                    <td><?php echo h($location['l_state']); ?></td>
                    <td><?php echo h($location['l_zipcode']); ?></td>
                    <td><?php echo h($location['l_pno']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/location/edit.php?l_id=' . h(u($location['l_id']))); ?>">Edit</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/location/delete.php?l_id=' . h(u($location['l_id']))); ?>">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php
        mysqli_free_result($location_set);
        ?>
    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
