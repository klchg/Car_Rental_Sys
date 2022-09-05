<?php require_once('../private/initialize.php'); require_customer_not_login();?>
<?php include(SHARED_PATH . '/public_header.php'); ?>
<div id="main">

<?php //include(SHARED_PATH . '/public_navigation.php'); ?>

<div id="page">
<!--    <div id=\"hero-image\">-->
<!--        <img src=\"images/page_assets/about us_96582054.png\" width=\"900\" height=\"200\" alt=\"\" />-->
<!--    </div>-->
    <h1>Welcome to WOW!</h1>
    <h2><a href="<?php echo url_for('pagelogin.php'); ?>">User Login</a></h2>
    <h2><a href="<?php echo url_for('pagesignup.php'); ?>">User Signup</a></h2>

</div>


</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>