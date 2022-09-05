<?php require_once('../../private/initialize.php'); ?>

<?php require_login(); ?>

<?php $page_title = 'Staff Menu'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div id="main-menu">
    <h2>Main Menu</h2>
    <ul>
        <li><a href="<?php echo url_for('/staff/finish_order/search.php'); ?>">Finish Order</a></li>
        <li><a href="<?php echo url_for('/staff/class/index.php'); ?>">Manage Class</a></li>
        <li><a href="<?php echo url_for('/staff/coupon/index.php'); ?>">Manage Coupon</a></li>
        <li><a href="<?php echo url_for('/staff/location/index.php'); ?>">Manage Location</a></li>
        <li><a href="<?php echo url_for('/staff/vehicle/index.php'); ?>">Manage Vehicle</a></li>
        <li><a href="<?php echo url_for('/staff/indiv/index.php'); ?>">Manage Individual Customer</a></li>
        <li><a href="<?php echo url_for('/staff/emp/index.php'); ?>">Manage Corporate Customer</a></li>
        <li><a href="<?php echo url_for('/staff/coop/index.php'); ?>">Manage Corporation</a></li>
        <li><a href="<?php echo url_for('/staff/service/index.php'); ?>">Manage Service</a></li>
        <li><a href="<?php echo url_for('/staff/admins/index.php'); ?>">Manage Admins</a></li>
    </ul>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
