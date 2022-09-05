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

require_customer_not_login();?>


<?php
    if(is_post_request()) {
        $customer = [];
        $customer['username'] = $_POST['username'] ?? '';
        $customer['password'] = $_POST['password'] ?? '';
        $customer['confirm_password'] = $_POST['confirm_password'] ?? '';

        $customer['c_type'] = 'I';
        $customer['c_email'] = $_POST['c_email'] ?? '';
        $customer["c_street"] = $_POST['c_street'] ?? '';
        $customer["c_city"] = $_POST['c_city'] ?? '';
        $customer["c_state"] = $_POST['c_state'] ?? '';
        $customer["c_zipcode"] = $_POST['c_zipcode'] ?? '';
        $customer['p_no'] = $_POST['p_no'] ?? '';
        $customer['i_fname'] = $_POST['i_fname'] ?? '';
        $customer['i_lname'] = $_POST['i_lname'] ?? '';
        $customer['dl_no'] = $_POST['dl_no'] ?? '';
        $customer["ins_c_name"] = $_POST['ins_c_name'] ?? '';
        $customer['ins_p_no'] = $_POST['ins_p_no'] ?? '';


        $result = insert_customer($customer);
        if($result === true) {
            $new_id = mysqli_insert_id($db); // jxx_customer.c_no
            $customer['c_no'] = $new_id;
            $result = insert_indiv($customer);
            if($result === true) {
                $result = insert_user($customer);
                if($result === true){
                    $new_id = mysqli_insert_id($db); //jxx_user.id
                    $_SESSION['message'] = 'Customer Created, log in now!';
                    redirect_to(url_for('pageshow.php?id=' . $new_id));
                }else{
                    $errors = $result;
                }
            }else{
                $errors = $result;
            }
        } else {
            $errors = $result;
        }

//      $result = insert_user($customer);
//      if($result === true) {
//        $new_id = mysqli_insert_id($db);
//        $_SESSION['message'] = 'Customer Created.';
//        redirect_to(url_for('pageshow.php?id=' . $new_id));
//      } else {
//        $errors = $result;
//      }
        
    } else {
      // display the blank form
        $customer = [];
        $customer["username"] = '';
        $customer['password'] = '';
        $customer['confirm_password'] = '';

        $customer['c_type'] = 'I';
        $customer["c_street"] = '';
        $customer["c_city"] = '';
        $customer["c_state"] = '';
        $customer["c_zipcode"] = '';
        $customer["c_email"] = '';
        $customer['p_no'] = '';
        $customer["i_fname"] = '';
        $customer["i_lname"] = '';
        $customer['dl_no'] = '';
        $customer["ins_c_name"] = '';
        $customer['ins_p_no'] = '';

    }
                
			?>


<section class="">
		<?php
			include 'header.php';
		?>

			<section class="caption">
			<a class="back-link" href="<?php echo url_for('index.php'); ?>">&laquo; Back to Home</a>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
		&nbsp;
			<form action="<?php echo url_for('pagesignup.php'); ?>" method="post">    
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Individual Client Signup Area</h3>
			<?php echo display_errors($errors); ?>
				<table height="600" align="center">
					<tr>
						<td>First Name:</td>
						<td><input type="text" name="i_fname" value="<?php echo h($customer['i_fname']); ?>" placeholder="Enter First Nmae" required></td>
					</tr>
                    <tr>
						<td>Last Name:</td>
						<td><input type="text" name="i_lname" value="<?php echo h($customer['i_lname']); ?>" placeholder="Enter Last Name" required></td>
					</tr>
                    <tr>
						<td>Username:</td>
						<td><input type="text" name="username" value="<?php echo h($customer['username']); ?>" placeholder="Enter Username" required></td>
					</tr>
                    <tr>
						<td>Email:</td>
						<td><input type="text" name="c_email" value="<?php echo h($customer['c_email']); ?>" placeholder="Enter Email" required></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" value="<?php echo h($customer['password']); ?>" placeholder="Enter Password" required></td>
					</tr>
                    <tr>
						<td>Confirm Password:</td>
						<td><input type="password" name="confirm_password" value="<?php echo h($customer['confirm_password']); ?>" placeholder="Confirm Password" required></td>
					</tr>
                    <tr>
                        <td>Street</td>
                        <td><input type="text" name="c_street" value="<?php echo h($customer['c_street']); ?>" placeholder="Street" required></td>
                    </tr>
                    <tr>
                        <td>City</td>
                        <td><input type="text" name="c_city" value="<?php echo h($customer['c_city']); ?>" placeholder="City" required></td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td><input type="text" name="c_state" value="<?php echo h($customer['c_state']); ?>" placeholder="State" required></td>
                    </tr>
                    <tr>
                        <td>Zipcode</td>
                        <td><input type="text" name="c_zipcode" value="<?php echo h($customer['c_zipcode']); ?>" placeholder="Zipcode" required></td>
                    </tr>
                    <tr>
                        <td>Phone Number</td>
                        <td><input type="text" name="p_no" value="<?php echo h($customer['p_no']); ?>" placeholder="Phone Number" required></td>
                    </tr>
                    <tr>
                        <td>Driver License Number</td>
                        <td><input type="text" name="dl_no" value="<?php echo h($customer['dl_no']); ?>" placeholder="Driver License Number" required></td>
                    </tr>
                    <tr>
                        <td>Insurance Company Name</td>
                        <td><input type="text" name="ins_c_name" value="<?php echo h($customer['ins_c_name']); ?>" placeholder="Insurance Company Name" required></td>
                    </tr>
                    <tr>
                        <td>Insurance Policy Number</td>
                        <td><input type="text" name="ins_p_no" value="<?php echo h($customer['ins_p_no']); ?>" placeholder="Insurance Policy Number" required></td>
                    </tr>

					<tr>
						<td style="text-align:right;"><a href="pagelogin.php">Login Here</a>&nbsp;&nbsp;&nbsp;</td>
						<td><input type="submit" name="submit" value="Signup"></td>
					</tr>
				</table>
			</form>

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





      


