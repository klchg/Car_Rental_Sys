<?php

require_once('../../private/initialize.php');

// here should be the code to check if the user is logged in
//require_login_customer();

//if(is_post_request()) {
//    if ((!isset($_POST['s_id'])) || empty($_POST['s_id']) || $_POST['s_id'] == '') 
//        redirect_to(url_for('myorders.php'));
//    $service = [];
//    $service['s_id'] = $_POST['s_id'];
//    $service['d_date'] = $_POST['d_date'] ?? '';
//    $service['daily_limit'] = $_POST['daily_limit'] ?? '';
//    if ((!isset($_POST['cou_id'])) || empty($_POST['cou_id']) || $_POST['cou_id'] == '')
//        $service['cou_id'] =  'NULL';
//    else
//        $service['cou_id'] = $_POST['cou_id'];
//    
//    $result = update_service($service);
//    if($result === true) {
//        $_SESSION['message'] = 'The order was updated successfully.';
//        redirect_to(url_for('myorders.php'));
//    } else {
//        $errors = $result;
//    }

//} else {
//    $service = [];
//    if  ((!isset($_GET['s_id'])) || (empty($_GET['s_id']))) {
//        redirect_to(url_for('myorders.php'));
//    } else {
//        $service['s_id'] = $_GET['s_id'];
//    }
//    
//    $service['pk_date'] = $_GET['pk_date'] ?? '';
//    $service['pk_street'] = $_GET['pk_street'] ?? 'Unknown';
//    $service['pk_city'] = $_GET['pk_city'] ?? 'Unknown';
//    $service['pk_state'] = $_GET['pk_state'] ?? "Unknown";
//
//}

?>

<?php $page_title = 'Search Order'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">

<!--    <a class="back-link" href="<?php echo url_for('Orders/myorders.php'); ?>">&laquo; Back to All My Orders</a>-->

    <div class="page new">
        <h1>Search Order</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/Orders/manage_orders.php'); ?>" method="get">

            <dl>
                <dt>customer number</dt>
                <dd>
                    <label for="c_no">Input customer number:</label>
                    <input name="c_no" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>order id</dt>
                <dd>
                    <label for="s_id">Input order id:</label>
                    <input name="s_id" type="text" />
                </dd>
            </dl>

            <div id="search_order">
                <input type="submit" value="Search" />
            </div>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
