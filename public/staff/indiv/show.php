<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$c_no = $_GET['c_no']; // PHP > 7.0

$indiv_set = find_indiv_by_id($c_no);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/indiv/index.php'); ?>">&laquo; Back to Individual Customer Page</a>

    <div class="coupon show">

        <h1>Details </h1>
        <?php while($indiv = mysqli_fetch_assoc($indiv_set)){ ?>
            <div class="attributes">
                <dl>
                    <dt>Firstname</dt>
                    <dd><?php echo h($indiv['i_fname']); ?></dd>
                </dl>
                <dl>
                    <dt>Lastname</dt>
                    <dd><?php echo h($indiv['i_lname']); ?></dd>
                </dl>
                <dl>
                    <dt>Street</dt>
                    <dd><?php echo h($indiv['c_street']); ?></dd>
                </dl>
                <dl>
                    <dt>City</dt>
                    <dd><?php echo h($indiv['c_city']); ?></dd>
                </dl>
                <dl>
                    <dt>State</dt>
                    <dd><?php echo h($indiv['c_state']); ?></dd>
                </dl>
                <dl>
                    <dt>Zipcode</dt>
                    <dd><?php echo h($indiv['c_zipcode']); ?></dd>
                </dl>
                <dl>
                    <dt>Email</dt>
                    <dd><?php echo h($indiv['c_email']); ?></dd>
                </dl>
                <dl>
                    <dt>Phone Number</dt>
                    <dd><?php echo h($indiv['p_no']); ?></dd>
                </dl>
                <dl>
                    <dt>Driver License Number</dt>
                    <dd><?php echo h($indiv['dl_no']); ?></dd>
                </dl>
                <dl>
                    <dt>Insurance Company Name</dt>
                    <dd><?php echo h($indiv['ins_c_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Insurance Policy Number</dt>
                    <dd><?php echo h($indiv['ins_p_no']); ?></dd>
                </dl>
            </div>
        <?php } mysqli_free_result($indiv_set); ?>


    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
