<!DOCTYPE html>
<html lang="en">
<head>
    <title>Car Rental</title>
    <meta charset="utf-8">
    <meta name="author" content="pixelhint.com">
    <meta name="description" content="La casa free real state fully responsive html5/css3 home page website template"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <link rel="stylesheet" type="text/css" href="../css/reset.css">
    <link rel="stylesheet" type="text/css" href="../css/responsive.css">

    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>
</head>
<body>

<?php

require_once('../../private/initialize.php');

require_login_customer();

if(is_post_request()) {
    if ((!isset($_POST['s_id'])) || empty($_POST['s_id']) || $_POST['s_id'] == '') 
        redirect_to(url_for('/Orders/myorders.php'));

    if ((!isset($_POST['method'])) || empty($_POST['method']) || $_POST['method'] == '') 
        redirect_to(url_for('/Orders/myorders.php'));

    if ((!isset($_POST['card_no'])) || empty($_POST['card_no']) || $_POST['card_no'] == '') 
        redirect_to(url_for('/Orders/myorders.php'));

    if ((!isset($_POST['p_amount'])) || empty($_POST['p_amount']) || $_POST['p_amount'] == '') 
        redirect_to(url_for('/Orders/myorders.php'));

    if ((!isset($_POST['vid'])) || empty($_POST['vid']) || $_POST['vid'] == '') 
        redirect_to(url_for('/Orders/myorders.php'));

    $service = [];
    $service['s_id'] = $_POST['s_id'];
    $service['p_date'] = date('Y-m-d H:i:s');
    $service['method'] = $_POST['method'];
    $service['card_no'] = $_POST['card_no'];
    $service['p_amount'] = $_POST['p_amount'];
    $service['vid'] = $_POST['vid'];
    
    $result = pay_invoice($service);
    if($result === true) {
        $_SESSION['message'] = 'The order was paid successfully.';
        redirect_to(url_for('/Orders/myorders.php'));
    } else {
        $errors = $result;
    }

} else {
    $service = [];
    if  ((!isset($_GET['s_id'])) || (empty($_GET['s_id']))) {
        redirect_to(url_for('/Orders/myorders.php'));
    } else {
        $service['s_id'] = $_GET['s_id'];
    }

    if ((!isset($_GET['vid'])) || (empty($_GET['vid']))) {
        redirect_to(url_for('/Orders/myorders.php'));
    } else {
        $service['vid'] = $_GET['vid'];
    }
    $invoice_info = mysqli_fetch_assoc(calculate_invoice_by_service_id($service['s_id']));
    $service['i_amount'] = $invoice_info['i_amount'];
}

?>

<?php $page_title = 'Pay Order'; ?>
<section class="">
    <?php
    include '../header.php';
    ?>

    <section class="caption">
        <a class="back-link" href="<?php echo url_for('/Orders/myorders.php'); ?>">&laquo; Back to All My Orders</a>
        <h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
        <h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
    </section>
</section><!--  end hero section  -->



<section class="search">
    <div class="wrapper">
        <div id="">
            <?php date_default_timezone_set('America/New_York'); ?>
            <div id="content">

    <div class="page new">
        <h1>Pay Order</h1>

        <?php echo display_errors($errors); ?>
        <h3>You need to pay $<?php echo $service['i_amount']; ?> for this order.</h3>

        <form action="<?php echo url_for('/Orders/pay_order.php'); ?>" method="post">
            <?php $user_id = $_SESSION['customer_id'];
            $customer = find_customer_id_by_user_id($user_id);?>
            <input type="hidden" name="s_id" value="<?php echo $service['s_id']?>"/>
            <input type="hidden" name="c_no" value="<?php echo $customer['c_no'] ?>"/>
            <input type="hidden" name="vid" value="<?php echo $service['vid']?>"/>
            <dl>
                <dt>Pay Method</dt>
                <dd>
                    <label for="Method">Select your pay method:</label>
                    <select name="method">
                        <option value="debit">debit</option>
                        <option value="credit">credit</option>
                        <option value="gift card">gift card</option>
                    </select>
<!--                    <input type="hidden" id="daily_limit"/>-->
                </dd>
            </dl>

            <dl>
                <dt>Card number</dt>
                <dd>
                    <label for="card_no">Input your card number:</label>
                    <input name="card_no" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Amount</dt>
                <dd>
                    <label for="p_amount">Input your payment amount:</label>
                    <input name="p_amount" type="text" />
                </dd>
            </dl>

            <?php echo ("s_id: " . $service['s_id']); ?>
            <?php echo ("            vid: ". $service['vid']); ?>
            <div id="pay_order">
                <input type="submit" value="Pay Order" />
            </div>

    </div>

</div>

        </div>
        <a href="#" class="advanced_search_icon" id="advanced_search_btn"></a>
    </div>

</section><!--  end search section  -->

<footer>
    <div class="wrapper footer">
        <ul>
            <li class="links">
                <ul>
                    <li>OUR COMPANY</li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Policy</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </li>

            <li class="links">
                <ul>
                    <li>OTHERS</li>
                    <li><a href="#">...</a></li>
                    <li><a href="#">...</a></li>
                    <li><a href="#">...</a></li>
                    <li><a href="#">...</a></li>
                </ul>
            </li>

            <li class="links">
                <ul>
                    <li>OUR CAR TYPES</li>
                    <li><a href="#">Mercedes</a></li>
                    <li><a href="#">Range Rover</a></li>
                    <li><a href="#">Landcruisers</a></li>
                    <li><a href="#">Others.</a></li>
                </ul>
            </li>

            <?php include_once "../includes/footer.php"; ?>

