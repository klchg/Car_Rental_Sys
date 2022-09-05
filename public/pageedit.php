
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

//$id = get_customer_id();
$user_id = $_SESSION['customer_id'];
$customer_id = find_customer_id_by_user_id($user_id);
$c_no = $customer_id['c_no'];

?>

<?php
if(is_post_request()) {
    $customer = [];
    $customer['id'] = $user_id;
    $customer['c_no'] = $c_no;
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

  
  $result = update_user($customer);
  if($result === true) {
      $result = update_customer($customer);
      if($result === true) {
          $result = update_indiv($customer);
          if($result === true){
              $_SESSION['message'] = 'Customer updated.';
              redirect_to(url_for('pageshow.php'));
          }else {
            $errors = $result;
          }
      }else {
        $errors = $result;
      }
//    redirect_to(url_for('pageshow.php?id=' . $id));
  } else {
    $errors = $result;
  }

} else {
//  $customer = find_customer_by_id($id);
    $customer = find_user_info_by_customer_id($customer_id['c_no']);
}

?>

<?php $page_title = 'Edit'; ?>
<section class="">
		<?php
			include 'header.php';
		?>

			<section class="caption">
			<td style="text-align:center;"><a href="pageshow.php">&laquo; Back to My Profile</a></td>
				<h2 class="caption" style="text-align: center">Find You Dream Cars For Hire</h2>
				<h3 class="properties" style="text-align: center">Range Rovers - Mercedes Benz - Landcruisers</h3>
			</section>
	</section><!--  end hero section  -->



	<section class="search">
		<div class="wrapper">
		<div id="fom">
			<form action="pageedit.php" method="post">
            
			<h3 style="text-align:center; color: #000099; font-weight:bold; text-decoration:underline">Edit My Profile</h3>
            <?php echo display_errors($errors); ?>
				<table height="600" align="center">
					<tr>
						<td>First Name:</td>
						<td><input type="text" name="i_fname" value="<?php echo h($customer['i_fname']) ?>" placeholder="Enter First Name" required></td>
					</tr>
                    <tr>
						<td>Last Name:</td>
						<td><input type="text" name="i_lname" value="<?php echo h($customer['i_lname']) ?>" placeholder="Enter Last Name" required></td>
					</tr>
                    <tr>
						<td>Username:</td>
						<td><input type="text" name="username" value="<?php echo h($customer['username']) ?>" placeholder="Enter Username" required></td>
					</tr>
                    <tr>
						<td>Email:</td>
						<td><input type="text" name="c_email" value="<?php echo h($customer['c_email']) ?>" placeholder="Enter Email" required></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="password" name="password" placeholder="Enter Password" required></td>
					</tr>
                    <tr>
						<td>Confirm Password:</td>
						<td><input type="password" name="confirm_password" placeholder="Confirm Password" required></td>
					</tr>
                    <tr>
                        <td>Street:</td>
                        <td><input type="text" name="c_street" value="<?php echo h($customer['c_street']) ?>" placeholder="Enter Street" required></td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td><input type="text" name="c_city" value="<?php echo h($customer['c_city']) ?>" placeholder="Enter City" required></td>
                    </tr>
                    <tr>
                        <td>State:</td>
                        <td><input type="text" name="c_state" value="<?php echo h($customer['c_state']) ?>" placeholder="Enter State" required></td>
                    </tr>
                    <tr>
                        <td>Zipcode:</td>
                        <td><input type="text" name="c_zipcode" value="<?php echo h($customer['c_zipcode']) ?>" placeholder="Enter Zipcode" required></td>
                    </tr>
                    <tr>
                        <td>Phone Number:</td>
                        <td><input type="text" name="p_no" value="<?php echo h($customer['p_no']) ?>" placeholder="Enter Phone Number" required></td>
                    </tr>
                    <tr>
                        <td>Driver License Number:</td>
                        <td><input type="text" name="dl_no" value="<?php echo h($customer['dl_no']) ?>" placeholder="Enter Driver License Number" required></td>
                    </tr>
                    <tr>
                        <td>Insurance Company Name：</td>
                        <td><input type="text" name="ins_c_name" value="<?php echo h($customer['ins_c_name']) ?>" placeholder="Enter Insurance Company Name" required></td>
                    </tr>
                    <tr>
                        <td>Insurance Policy Number：</td>
                        <td><input type="text" name="ins_p_no" value="<?php echo h($customer['ins_p_no']) ?>" placeholder="Enter Insurance Policy Number" required></td>
                    </tr>

                    <tr>
                        <td><input type="submit" name="submit" value="Edit"></td>
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


