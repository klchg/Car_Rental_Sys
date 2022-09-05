<!DOCTYPE html>
<html lang="en">
<head>
	<title>Car Rental</title>
	<meta charset="utf-8">
	<meta name="author" content="pixelhint.com">
	<meta name="description" content="La casa free real state fully responsive html5/css3 home page website template"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
	
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/responsive.css">

	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body>

<?php

require_once('../private/initialize.php');

require_login_customer();

if(is_post_request()) {

//    $search = [];
//    $search['make'] = $_POST['make'] ?? '';
//    $search['l_city'] = $_POST['l_city'] ?? '';
//
//    $available_set = find_vehicles_by_brand_and_city($search);

    $search = [];
    $search['class_name'] = $_POST['class_name'] ?? '';
    $search['make'] = $_POST['make'] ?? '';
    $search['vin'] = $_POST['vin'] ?? '';
    $search['l_street'] = $_POST['l_street'] ?? '';
    $search['l_city'] = $_POST['l_city'] ?? '';
    $search['l_state'] = $_POST['l_state'] ?? '';
    $search['l_zipcode'] = $_POST['l_zipcode'] ?? '';

    $result = find_vehicle_by_factors($search);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $search = [];
    $search['class_name'] = '';
    $search['make'] = '';
    $search['vin'] = '';
    $search['l_street'] = '';
    $search['l_city'] = '';
    $search['l_state'] = '';
    $search['l_zipcode'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Vehicle Search'; ?>
<section class="">
		<?php
			include 'header.php';
		?>
        
			<section class="caption">
            <a class="back-link" href="<?php echo url_for('pagetwo.php'); ?>">&laquo; Back to User Home</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
			
        <div class="vehicle search">
        &nbsp;
        <h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline"> Search available vehicle</h3>
        <dl>
            <dt><?php echo $_SESSION['message'] ?? ''; $_SESSION['message'] = ''; ?></dt>
        </dl>
        <form action="<?php echo url_for('vehicle_search.php'); ?>" method="post">
            <dl>
                <dt>Class</dt>
                <dd>
                    <select name="class_name">
                        <option value=""></option>
                        <?php
                        $class_set = find_all_class_name();
                        while($class = mysqli_fetch_assoc($class_set)) {
                            echo "<option value=\"" . h($class['class_name']) . "\"";
                            if($search['class_name'] == $class['class_name']) {
                                echo " selected";
                            }
                            echo ">" . h($class['class_name']) . "</option>";
                        }
                        mysqli_free_result($class_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Brand</dt>
                <dd>
                    <select name="make">
                        <option value=""></option>
                        <?php
                        $brand_set = find_all_brand();
                        while($brand = mysqli_fetch_assoc($brand_set)) {
                            echo "<option value=\"" . h($brand['make']) . "\"";
                            if($search['make'] == $brand['make']) {
                                echo " selected";
                            }
                            echo ">" . h($brand['make']) . "</option>";
                        }
                        mysqli_free_result($brand_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Pick up Location Zipcode</dt>
                <dd>
                    <input name="l_zipcode" type="text" />
                </dd>
            </dl>
            <dl>
                <dt>Pick up State</dt>
                <dd>
                    <select name="l_state">
                        <option value=""></option>
                        <?php
                        $state_set = find_all_states();
                        while($state = mysqli_fetch_assoc($state_set)) {
                            echo "<option value=\"" . h($state['l_state']) . "\"";
                            if($search['l_state'] == $state['l_state']) {
                                echo " selected";
                            }
                            echo ">" . h($state['l_state']) . "</option>";
                        }
                        mysqli_free_result($state_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Pick up City</dt>
                <dd>
                    <select name="l_city">
                        <option value=""></option>
                        <?php
                        $city_set = find_all_cities();
                        while($city = mysqli_fetch_assoc($city_set)) {
                            echo "<option value=\"" . h($city['l_city']) . "\"";
                            if($search['l_city'] == $city['l_city']) {
                                echo " selected";
                            }
                            echo ">" . h($city['l_city']) . "</option>";
                        }
                        mysqli_free_result($city_set);
                        ?>

                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Pick up Street</dt>
                <dd>
                    <select name="l_street">
                        <option value=""></option>
                        <?php
                        $street_set = find_all_streets();
                        while($street = mysqli_fetch_assoc($street_set)) {
                            echo "<option value=\"" . h($street['l_street']) . "\"";
                            if($search['l_street'] == $street['l_street']) {
                                echo " selected";
                            }
                            echo ">" . h($street['l_street']) . "</option>";
                        }
                        mysqli_free_result($street_set);
                        ?>
                    </select>
                </dd>
            </dl>
            &nbsp;
            <div id="operations">
                
                <input type="submit" value="Search" />
            </div>
            &nbsp;
        </form>

        
        <?php if($available_set!=[]){?>
        <table class="list">
            <tr>
                <th><h5 style="text-align:left; color: black; font-weight:bold; ">Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th><h5 style="text-align:left; color: black; font-weight:bold; ">Class &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th><h5 style="text-align:left; color: black; font-weight:bold; ">Rental Rate &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th><h5 style="text-align:left; color: black; font-weight:bold; ">Over Fee &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php
            while($available = mysqli_fetch_assoc($available_set)){ ?>

                <tr>
<!--                    <td>--><?php //echo h($available['vid']); ?><!--</td>-->
                    <td><h6 style="text-align:center color:#000099 "><?php echo h($available['make']); ?></td>
                    <td><h6 style="text-align:center color:#000099 "><?php echo h($available['class_name']); ?></td>
                    <td><h6 style="text-align:center color:#000099 "><?php echo h($available['rental_rate']); ?></td>
                    <td><h6 style="text-align:center color:#000099 "><?php echo h($available['over_fee']); ?></td>
<!--                    <td>--><?php //echo h($available['year']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['rental_rate']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['rental_rate']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['over_fee']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['l_id']); ?><!--</td>-->
                    <td><a class="action" href="<?php echo url_for('/Orders/neworder.php?&vid=' . h(u($available['vid'])) . '&pk_l_id=' . h(u($available['l_id'])));?>">Select</a></td>
                </tr>
            <?php } ?>
        </table>

        <?php mysqli_free_result($available_set); }?>

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

			<?php include_once "includes/footer.php"; ?>




