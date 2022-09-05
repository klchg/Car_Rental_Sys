<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$l_id = $_GET['l_id']; // PHP > 7.0

$location_set = find_location_by_id($l_id);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/location/index.php'); ?>">&laquo; Back to Location Page</a>

    <div class="class show">

        <h1>Details </h1>
        <?php while($location = mysqli_fetch_assoc($location_set)){ ?>
        <div class="attributes">
            <dl>
                <dt>Street</dt>
                <dd><?php echo h($location['l_street']); ?></dd>
            </dl>
            <dl>
                <dt>City</dt>
                <dd><?php echo h($location['l_city']); ?></dd>
            </dl>
            <dl>
                <dt>State</dt>
                <dd><?php echo h($location['l_state']); ?></dd>
            </dl>
            <dl>
                <dt>Zipcode</dt>
                <dd><?php echo h($location['l_zipcode']); ?></dd>
            </dl>
            <dl>
                <dt>Phone Number</dt>
                <dd><?php echo h($location['l_pno']); ?></dd>
            </dl>
        </div>
        <?php } mysqli_free_result($location_set); ?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
