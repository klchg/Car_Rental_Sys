<?php require_once('../private/initialize.php'); ?>

<?php require_login_customer(); ?>

<?php $page_title = 'User Home'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

    <div id="content">
        <div id="main-menu">
            <h2>User Home</h2>
            <ul>
                <li><a href="<?php echo url_for('myorders.php'); ?>">My Orders History</a></li>
                <li><a href="<?php echo url_for('vehicle_search.php'); ?>">Create New Order</a></li>
                <li><a href="<?php echo url_for('pagelogout.php'); ?>">Logout</a></li>


            </ul>
        </div>

    </div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>