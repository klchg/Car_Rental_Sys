<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $vehicle = [];
    $vehicle['class_name'] = $_POST['class_name'] ?? '';
    $vehicle['make'] = $_POST['make'] ?? '';
    $vehicle['vin'] = $_POST['vin'] ?? '';
    $vehicle['l_street'] = $_POST['l_street'] ?? '';
    $vehicle['l_city'] = $_POST['l_city'] ?? '';
    $vehicle['l_state'] = $_POST['l_state'] ?? '';
    $vehicle['l_zipcode'] = $_POST['l_zipcode'] ?? '';

    $result = find_vehicle_by_factors($vehicle);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $vehicle = [];
    $vehicle['class_name'] = '';
    $vehicle['make'] = '';
    $vehicle['vin'] = '';
    $vehicle['l_street'] = '';
    $vehicle['l_city'] = '';
    $vehicle['l_state'] = '';
    $vehicle['l_zipcode'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Vehicle Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/vehicle/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class search">
        <h1>Search Vehicle</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/vehicle/search.php'); ?>" method="post">
            <dl>
                <dt>Class</dt>
                <dd>
                    <select name="class_name">
                        <option value=""></option>
                        <?php
                        $class_set = find_all_class_name();
                        while($class = mysqli_fetch_assoc($class_set)) {
                            echo "<option value=\"" . h($class['class_name']) . "\"";
                            if($vehicle['class_name'] == $class['class_name']) {
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
                            if($vehicle['make'] == $brand['make']) {
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
                <dt>VIN</dt>
                <dd>
                    <input name="vin" type="text" />
                </dd>
            </dl>
            <dl>
                <dt>Zipcode</dt>
                <dd>
                    <input name="l_zipcode" type="text" />
                </dd>
            </dl>
            <dl>
                <dt>State</dt>
                <dd>
                    <select name="l_state">
                        <option value=""></option>
                        <?php
                        $state_set = find_all_states();
                        while($state = mysqli_fetch_assoc($state_set)) {
                            echo "<option value=\"" . h($state['l_state']) . "\"";
                            if($vehicle['l_state'] == $state['l_state']) {
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
                <dt>City</dt>
                <dd>
                    <select name="l_city">
                        <option value=""></option>
                        <?php
                        $city_set = find_all_cities();
                        while($city = mysqli_fetch_assoc($city_set)) {
                            echo "<option value=\"" . h($city['l_city']) . "\"";
                            if($vehicle['l_city'] == $city['l_city']) {
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
                <dt>Street</dt>
                <dd>
                    <select name="l_street">
                        <option value=""></option>
                        <?php
                        $street_set = find_all_streets();
                        while($street = mysqli_fetch_assoc($street_set)) {
                            echo "<option value=\"" . h($street['l_street']) . "\"";
                            if($vehicle['l_street'] == $street['l_street']) {
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
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Class</th>
                    <th>Brand</th>
                    <th>Rental Rate</th>
                    <th>Over Limit Fee</th>
                    <th>Made Year</th>
                    <th>VIN</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['make']); ?></td>
                        <td><?php echo h($available['class_name']); ?></td>
                        <td><?php echo h($available['rental_rate']); ?></td>
                        <td><?php echo h($available['over_fee']); ?></td>
                        <td><?php echo h($available['year']); ?></td>
                        <td><?php echo h($available['vin']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/vehicle/show.php?vid=' . h(u($available['vid']))); ?>">View</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/vehicle/edit.php?vid=' . h(u($available['vid']))); ?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/vehicle/delete.php?vid=' . h(u($available['vid']))); ?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
