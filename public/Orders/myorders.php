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
date_default_timezone_set('America/New_York');
require_login_customer();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$user_id = $_SESSION['customer_id'];
$customer = find_customer_id_by_user_id($user_id);
$order_set = find_orders_by_customer_id($customer['c_no']);
$going_set = [];
$unstarted_orders =[];
//$id = $_GET['id'] ?? '1'; // PHP > 7.0
//
//$order = find_order_by_id($id);
//$subject = find_subject_by_id($page['subject_id']);

?>




<?php $page_title = 'My Orders'; ?>
<section class="">
		<?php
			include '../header.php';
		?>

			<section class="caption">
            <a class="back-link" href="<?php echo url_for('../public/pagetwo.php'); ?>">&laquo; Back to User Home</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
        &nbsp;
			 <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline"> Show all my orders</h3>
             &nbsp;
        </div>
        <dl>
            <dt><?php echo $_SESSION['message'] ?? ''; $_SESSION['message'] = ''; ?></dt>
        </dl>

        <div class="orders show">
            <h4>Completed Orders</h4>
<!--            --><?php //if($order_set!=[]){?>
            <?php if(mysqli_num_rows($order_set)>0){?>
                <table class="list">
                    <tr>
                    <th><h5 style="text-align:left; color: black; font-weight:bold; ">Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Loc &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Location &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Daily Limit &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Rental Rate &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Over Fee &nbsp;  &nbsp; &nbsp;  &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Coupon Discount</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php
                    while($order = mysqli_fetch_assoc($order_set)){ 
                        if (!isset($order['is_complete']) || $order['is_complete'] == 0) {
                            array_push($going_set, $order);
                        } else {
                    ?>

                        <tr>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['make']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['pk_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['d_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['daily_limit']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['rental_rate']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['over_fee']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['cou_discount']); ?></td>
                            <!--<td><a class="action" href="<?php echo url_for('/Orders/edit.php?&s_id=' . h(u($order['s_id'])) . '&pk_date='. h(u($order['pk_date'])) . '&pk_street='. h(u($order['pk_street'])) . '&pk_city='. h(u($order['pk_city'])) . '&pk_state='. h(u($order['pk_state'])));?>">Edit&nbsp;</a></td>-->
                            <td><a class="action" href="<?php echo url_for('/Orders/pay_order.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Pay &nbsp; </a></td>
<!--                            <td><a class="action" href="--><?php //echo url_for('/Orders/delete_confirm.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?><!--"> &nbsp; Delete</a></td>-->
                        </tr>
                    <?php }} ?>
                </table>
            <?php mysqli_free_result($order_set); }
            else{?>
                <p>No Orders</p>
            <?php }?>

            <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline"></h3>

            <h4>Going Orders</h4>
            <?php if($going_set!=[]){?>
                <table class="list">
                    <tr>
                    <th><h5 style="text-align:left; color: black; font-weight:bold; ">Brand&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Loc&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Location &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Daily Limit &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Rental Rate &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Over Fee &nbsp;  &nbsp; &nbsp;  &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Coupon Discount</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php
                    foreach($going_set as $key => $order){ 
                        if (!isset($order['pk_date']) || empty($order['pk_date']) ||$order['pk_date'] == '' || strtotime($order['pk_date']) > time()) {
                            array_push($unstarted_orders, $order);
                        } else {
                    ?>

                        <tr>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['make']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['pk_date']), 0, 10);; ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['pk_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['d_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['daily_limit']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['rental_rate']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['over_fee']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['cou_discount']); ?></td>
                            <td><a class="action" href="<?php echo url_for('/Orders/edit.php?&s_id=' . h(u($order['s_id'])) . '&pk_date='. h(u($order['pk_date'])) . '&pk_street='. h(u($order['pk_street'])) . '&pk_city='. h(u($order['pk_city'])) . '&pk_state='. h(u($order['pk_state'])));?>">Edit&nbsp;</a></td>
                            <!--<td><a class="action" href="<?php echo url_for('/Orders/pay_order.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Pay &nbsp; </a></td>-->
                            <!--<td><a class="action" href="<?php echo url_for('/Orders/delete_confirm.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Delete</a></td>-->
                        </tr>
                    <?php }} ?>
                </table>
            <?php }
            else{?>
                <p>No Orders</p>
            <?php }?>

            <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline"></h3>
            
            <h4>Unstarted Orders</h4>
            <?php if($unstarted_orders!=[]){?>
                <table class="list">
                    <tr>
                    <th><h5 style="text-align:left; color: black; font-weight:bold; ">Brand&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Pick up Loc&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Drop Location &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Daily Limit &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Rental Rate &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Over Fee &nbsp;  &nbsp; &nbsp;  &nbsp;</th>
                        <th><h5 style="text-align:left; color: black; font-weight:bold; ">Coupon Discount</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>

                    <?php
                    foreach($unstarted_orders as $key => $order){ ?>

                        <tr>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['make']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['pk_date']), 0, 10);; ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['pk_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['d_city']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['daily_limit']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['rental_rate']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['over_fee']); ?></td>
                            <td><h6 style="text-align:center color:#000099 "><?php echo h($order['cou_discount']); ?></td>
                            <td><a class="action" href="<?php echo url_for('/Orders/edit.php?&s_id=' . h(u($order['s_id'])) . '&pk_date='. h(u($order['pk_date'])) . '&pk_street='. h(u($order['pk_street'])) . '&pk_city='. h(u($order['pk_city'])) . '&pk_state='. h(u($order['pk_state'])));?>">Edit&nbsp;</a></td>
                            <!--<td><a class="action" href="<?php echo url_for('/Orders/pay_order.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Pay &nbsp; </a></td>-->
                            <td><a class="action" href="<?php echo url_for('/Orders/delete_confirm.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Delete</a></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php }
            else{?>
                <p>No Orders</p>
            <?php }?>


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






