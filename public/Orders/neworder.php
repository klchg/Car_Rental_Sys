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


<?php

require_once('../../private/initialize.php');

require_login_customer();

date_default_timezone_set('America/New_York');

if(is_post_request()) {
    $service = [];
    $service['pk_date'] = $_POST['pk_date'] ?? '';
    $service['d_date'] = $_POST['d_date'] ?? '';
    $service['daily_limit'] = $_POST['daily_limit'] ?? '';
    $service['vid'] = $_POST['vid'] ?? '';
    $service['pk_l_id'] = $_POST['pk_l_id'] ?? '';
    $service['c_no'] = $_POST['c_no'] ?? '';

    $coupon = find_coupon_id_by_no($_POST['cou_no']);
    $service['cou_id'] = $coupon['cou_id'] ?? '';

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
    
    $result = insert_service($service);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The order was created successfully.';
        redirect_to(url_for('/Orders/myorders.php'));
    } else {
        $errors = $result;
        redirect_to(url_for('vehicle_search.php'));
    }

} else {

    $service = [];
    $service['vid'] = $_GET['vid']??'';
    $service['pk_l_id'] = $_GET['pk_l_id']??'';
    $user_id = $_SESSION['customer_id'];
    $customer = find_customer_id_by_user_id($user_id);
    $service['c_no'] = $customer['c_no'];
}

?>

<?php $page_title = 'Create Order'; ?>
<section class="">
		<?php
			include '../header.php';
		?>

			<section class="caption">
            <a class="back-link" href="<?php echo url_for('vehicle_search.php'); ?>">&laquo; Back to Vehicle Search</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
			<form action="neworder.php" method="post">
            &nbsp;
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Create Order</h3>
            <?php echo display_errors($errors); ?>
            <div id="content">

                <dl>
                    <dt><?php echo $_SESSION['message'] ?? ''; $_SESSION['message'] = ''; ?></dt>
                </dl>
            </div>
        </div>
        <div class="page new">
    
        
        <?php echo display_errors($errors); ?>

            <form action="<?php echo url_for('/Orders/neworder.php'); ?>" method="post">
                <input type="hidden" name="vid" value="<?php echo $service['vid']?>"/>
                <input type="hidden" name="pk_l_id" value="<?php echo $service['pk_l_id']?>"/>
                <input type="hidden" name="c_no" value="<?php echo $service['c_no']?>"/>

                <dl>
                &nbsp;
                    <dd>
                        <label for="pk_date">Select your pick up date:&nbsp;</label>
    <!--                    --><?php //$date = date("Y-m-d\TH:i:s", strtotime($result['schedule']));?>
                        <input name="pk_date" type="date" value='NULL'/>
                    </dd>
                </dl>

                <dl>
                &nbsp;
                    <dd>
                        <label for="d_date">Select your drop date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
    <!--                    --><?php //$date = date("Y-m-d\TH:i:s", strtotime($result['schedule']));?>
                        <input name="d_date" type="date" />
                    </dd>
                </dl>

                <dl>
                &nbsp;
                    <dd>
                        <label for="daily_limit">Select your daily limit:&nbsp;&nbsp;&nbsp;&nbsp;</label>
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
                         $location_set = find_location_by_id($service['pk_l_id']);
                         while($location = mysqli_fetch_assoc($location_set)){
                             echo h($location['l_street']) . ", " . h($location['l_city']) . ", " . h($location['l_state']);
                        }
                        ?>
                    </dd>
                </dl>

                <dl>
                &nbsp;
                    <dd>
                        <label for="coupon">Enter your coupon number:</label>
                        <input name="cou_no" type="text" />
                    </dd>
                </dl>
                &nbsp;
                <div id="create_order">
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    &nbsp;<input type="submit" value="Create Order" />
                </div>
                &nbsp;
            </form>
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



















