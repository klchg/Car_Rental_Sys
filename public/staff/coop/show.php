<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$coop_id = $_GET['coop_id']; // PHP > 7.0

$coop_set = find_coop_by_id($coop_id);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/coop/index.php'); ?>">&laquo; Back to Corporation Page</a>

    <div class="coupon show">

        <h1>Details </h1>
        <?php while($coop = mysqli_fetch_assoc($coop_set)){ ?>
            <div class="attributes">
                <dl>
                    <dt>Corporation Name</dt>
                    <dd><?php echo h($coop['c_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Registration Number</dt>
                    <dd><?php echo h($coop['reg_no']); ?></dd>
                </dl>
                <dl>
                    <dt>Discount Rate</dt>
                    <dd><?php echo h($coop['c_rate']); ?></dd>
                </dl>
            </div>
        <?php } mysqli_free_result($coop_set); ?>


    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
