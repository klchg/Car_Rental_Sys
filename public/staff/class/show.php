<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$c_id = $_GET['c_id']; // PHP > 7.0

$class_set = find_class_by_id($c_id);

?>

<?php $page_title = 'Show Detail'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/class/index.php'); ?>">&laquo; Back to Class Page</a>

    <div class="class show">

        <h1>Details </h1>
        <?php while($class = mysqli_fetch_assoc($class_set)){ ?>
        <div class="attributes">
            <dl>
                <dt>Class Name</dt>
                <dd><?php echo h($class['class_name']); ?></dd>
            </dl>
            <dl>
                <dt>Rental Rate</dt>
                <dd><?php echo h($class['rental_rate']); ?></dd>
            </dl>
            <dl>
                <dt>Over Limit Fee</dt>
                <dd><?php echo h($class['over_fee']); ?></dd>
            </dl>
        </div>
        <?php } mysqli_free_result($class_set); ?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
