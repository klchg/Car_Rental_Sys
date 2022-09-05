<?php

require_once('../../../private/initialize.php');
date_default_timezone_set('America/New_York');

require_login();

if(!isset($_GET['vin']) or !isset($_GET['username'])) {
    redirect_to(url_for('/staff/index.php'));
}

$vin = $_GET['vin'];
$username = $_GET['username'];

if(is_post_request()) {

    // Handle form values sent by new.php
    $service = [];
    $service['vin'] = $vin;
    $service['username'] = $username;
    $service['e_odom'] = $_POST['e_odom'] ?? '';
    $service['d_date'] = $_POST['d_date'] ?? time();
    $service['l_zipcode'] = $_POST['l_zipcode'] ?? '';
    $service['l_street'] = $_POST['l_street'] ?? '';
    $service['l_city'] = $_POST['l_city'] ?? '';
    $service['l_state'] = $_POST['l_state'] ?? '';
    $service['d_l_id'] = '';
    $service['vid'] = '';
    $service['c_no'] = '';

    $result1 = find_location_id($service);
    if($result1!=[]){
        $service['d_l_id'] = $result1['l_id'];
    } else{
        $_SESSION['message'] = 'Invalid drop location.';
    }

    $result2 = find_vehicle_by_vin($vin);
    if($result2!=[]){
        $service['vid'] = $result2['vid'];
    } else{
        $_SESSION['message'] = 'Invalid VIN.';
    }

    $result3 = find_customer_by_username($username);
    if($result3!=[]){
        $service['c_no'] = $result3['c_no'];
    } else{
        $_SESSION['message'] = 'Invalid username.';
    }

    $result = finish_order($service);
    if($result === true){
        $_SESSION['message'] = 'The order was finished.';
        redirect_to(url_for('/staff/finish_order/search.php'));
    } else {
        $errors = $result;
    }

} else {

    $service = [];
    $service['vin'] = $vin;
    $service['username'] = $username;
    $service['e_odom'] = '';
    $service['d_date'] = '';
    $service['l_zipcode'] = '';
    $service['l_street'] = '';
    $service['l_city'] = '';
    $service['l_state'] = '';
    $service['d_l_id'] = '';
    $service['vid'] = '';
    $service['c_no'] = '';

}

?>

<?php $page_title = 'Finish Order'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/finish_order/search.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="coupon edit">
        <h1>Finish Order</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/finish_order/finish.php?&vin=' . h(u($service['vin'])) . '&username='. h(u($service['username']))); ?>" method="post">

            <dl>
                <dt>End Odometer</dt>
                <dd><input type="text" name="e_odom" value="<?php echo h($service['e_odom']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Drop date</dt>
                <dd><input type="date" name="d_date" value="<?php echo h($service['d_date']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Drop Zipcode</dt>
                <dd>
                    <input name="l_zipcode" type="text" />
                </dd>
            </dl>
            <dl>
                <dt>Drop State</dt>
                <dd>
                    <select name="l_state">
                        <option value=""></option>
                        <?php
                        $state_set = find_all_states();
                        while($state = mysqli_fetch_assoc($state_set)) {
                            echo "<option value=\"" . h($state['l_state']) . "\"";
                            if($service['l_state'] == $state['l_state']) {
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
                <dt>Drop City</dt>
                <dd>
                    <select name="l_city">
                        <option value=""></option>
                        <?php
                        $city_set = find_all_cities();
                        while($city = mysqli_fetch_assoc($city_set)) {
                            echo "<option value=\"" . h($city['l_city']) . "\"";
                            if($service['l_city'] == $city['l_city']) {
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
                <dt>Drop Street</dt>
                <dd>
                    <select name="l_street">
                        <option value=""></option>
                        <?php
                        $street_set = find_all_streets();
                        while($street = mysqli_fetch_assoc($street_set)) {
                            echo "<option value=\"" . h($street['l_street']) . "\"";
                            if($service['l_street'] == $street['l_street']) {
                                echo " selected";
                            }
                            echo ">" . h($street['l_street']) . "</option>";
                        }
                        mysqli_free_result($street_set);
                        ?>
                    </select>
                </dd>
            </dl>

                <div id="operations">
                    <input type="submit" value="Finish Order" />
                </div>

        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
