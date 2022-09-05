<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$c_no = $_GET['c_no']; // PHP > 7.0

$emp_set = find_emp_by_id($c_no);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/emp/index.php'); ?>">&laquo; Back to Corporate Customer Page</a>

    <div class="coupon show">

        <h1>Details </h1>
        <?php while($emp = mysqli_fetch_assoc($emp_set)){ ?>
            <div class="attributes">
                <dl>
                    <dt>Corporate Customer ID</dt>
                    <dd><?php echo h($emp['emp_id']); ?></dd>
                </dl>
                <dl>
                    <dt>Corporation Name</dt>
                    <dd><?php echo h($emp['c_name']); ?></dd>
                </dl>
                <dl>
                    <dt>Email Address</dt>
                    <dd><?php echo h($emp['c_email']); ?></dd>
                </dl>
                <dl>
                    <dt>Phone Number</dt>
                    <dd><?php echo h($emp['p_no']); ?></dd>
                </dl>
                <dl>
                    <dt>Corporate Discount Rate</dt>
                    <dd><?php echo h($emp['c_rate']); ?></dd>
                </dl>
            </div>
        <?php } mysqli_free_result($emp_set); ?>


    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
