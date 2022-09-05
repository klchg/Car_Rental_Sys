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
    $service = [];
    $service['s_id'] = $_POST['s_id'];
    $service['d_date'] = $_POST['d_date'] ?? '';
    $service['pk_date'] = $_POST['pk_date'] ?? '';
    $service['daily_limit'] = $_POST['daily_limit'] ?? '';

    //验证pk_date和d_date是否合法
    if (isset($service['pk_date']) && (!empty($service['pk_date'])) && $service['pk_date'] != '' 
    && isset($service['d_date']) && (!empty($service['d_date'])) && $service['d_date'] != '')
       if (strtotime($service['d_date'])<strtotime($service['pk_date'])){
           $_SESSION['message'] = 'The pickup date must be earlier than the drop date.';
            redirect_to(url_for('/Orders/neworder.php'));
       }elseif(strtotime($service['pk_date']) < time()){
           $_SESSION['message'] = 'The pickup date must be later than the current date.';
           redirect_to(url_for('/Orders/neworder.php'));
       }

    $coupon = find_coupon_id_by_no($_POST['cou_no']);
    $service['cou_id'] = $coupon['cou_id'] ?? '';
    
    $result = update_service($service);
    if($result === true) {
        $_SESSION['message'] = 'The order was updated successfully.';
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
    
    $service['pk_date'] = $_GET['pk_date'] ?? '';
    $service['pk_street'] = $_GET['pk_street'] ?? 'Unknown';
    $service['pk_city'] = $_GET['pk_city'] ?? 'Unknown';
    $service['pk_state'] = $_GET['pk_state'] ?? "Unknown";

}

?>

<?php $page_title = 'Update Order'; ?>
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
		<div id="fom">
            
        &nbsp;
			 <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline"> Update Order</h3>
             &nbsp;
        </div>

<?php echo display_errors($errors); ?>

<form action="<?php echo url_for('/Orders/edit.php'); ?>" method="post">
    <?php $user_id = $_SESSION['customer_id'];
    $customer = find_customer_id_by_user_id($user_id);?>
    <input type="hidden" name="s_id" value="<?php echo $service['s_id']?>"/>
    <input type="hidden" name="pk_date" value="<?php echo $service['pk_date']?>"/>
    <input type="hidden" name="c_no" value="<?php echo $customer['c_no'] ?>"/>

    <dl>
 
        <dd>
            <tr>
            <td><label for="d_date">Select your drop date:</label></td>
<!--                    --><?php //$date = date("Y-m-d\TH:i:s", strtotime($result['schedule']));?>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <input name="d_date" type="date" />
</tr>
        </dd>
    </dl>

    <dl>
    &nbsp;
        <dd>
            <label for="daily_limit">Select your daily odometer limit:</label>
            <select name="daily_limit">
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="1500">1500</option>
                <option value="2000">2000</option>
                <option value="2500">2500</option>
                <option value="3000">3000</option>
                <option value="3500">3500</option>
                <option value="4000">4000</option>
                <option value="4500">4500</option>
                <option value="5000">5000</option>
                <option value="NULL">No Limit</option>
            </select>
<!--                    <input type="hidden" id="daily_limit"/>-->
        </dd>
    </dl>

    <dl>
    &nbsp;
        <dd>
            <span>Your pick up address is: </span>
            <?php
                echo h($service['pk_street']) . ", " . h($service['pk_city']) . ", " . h($service['pk_state']);
            ?>
        </dd>
    </dl>

    <dl>
    &nbsp;
        <dd>
            <label for="coupon">Input your coupon number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input name="cou_no" type="text" />
        </dd>
    </dl>
    &nbsp;
    <div id="update_order">
    &nbsp;
        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <input type="submit" value="Update Order" />
    </div>
		</div>
        &nbsp;
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






















