<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $vehicle = [];
    $vehicle['make'] = $_POST['make'] ?? '';
    $vehicle['model'] = $_POST['model'] ?? '';
    $vehicle['year'] = $_POST['year'] ?? '';
    $vehicle['vin'] = $_POST['vin'] ?? '';
    $vehicle['lic_p_no'] = $_POST['lic_p_no'] ?? '';
    $vehicle['l_id'] = $_POST['l_id'] ?? '';
    $vehicle['class_name'] = $_POST['class_name'] ?? '';

    $result_set = find_class_by_name($vehicle);
    while($result = mysqli_fetch_assoc($result_set)){
        $vehicle['c_id'] = $result['c_id'] ?? '';
    }

    $result = insert_vehicle($vehicle);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The vehicle was created successfully.';
        redirect_to(url_for('/staff/vehicle/show.php?vid=' . $new_id));
    } else {
        $errors = $result;
    }

} else {

    $vehicle = [];
    $vehicle['make'] = '';
    $vehicle['model'] = '';
    $vehicle['year'] = '';
    $vehicle['vin'] = '';
    $vehicle['lic_p_no'] = '';
    $vehicle['l_id'] = '';
    $vehicle['c_id'] = '';
    $vehicle['class_name'] = '';

}

?>

<?php $page_title = 'Create Vehicle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/vehicle/index.php'); ?>">&laquo; Back to Vehicle Page</a>

    <div class="location new">
        <h1>Create Vehicle</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/vehicle/new.php'); ?>" method="post">
            <dl>
                <dt>Brand</dt>
                <dd><input type="text" name="make" value="<?php echo h($vehicle['make']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Model Number</dt>
                <dd><input type="text" name="model" value="<?php echo h($vehicle['model']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Made Year</dt>
                <dd><input type="date" name="year" value="<?php echo h($vehicle['year']); ?>" /></dd>
            </dl>
            <dl>
                <dt>VIN</dt>
                <dd><input type="text" name="vin" value="<?php echo h($vehicle['vin']); ?>" /></dd>
            </dl>
            <dl>
                <dt>License Plate Number</dt>
                <dd><input type="text" name="lic_p_no" value="<?php echo h($vehicle['lic_p_no']); ?>" /></dd>
            </dl>
            <dl>
                <dt>Location</dt>
                <dd>
                    <select name="l_id">
                        <option value=""></option>
                        <?php
                        $location_set = find_all_location();
                        while($location = mysqli_fetch_assoc($location_set)) {
                            echo "<option value=\"" . h($location['l_id']). "\"";
                            if($vehicle['l_id'] == $location['l_id']) {
                                echo " selected";
                            }
                            echo ">" .h($location['l_street']).", ".h($location['l_city']).", ".h($location['l_state'])."</option>";
                        }
                        mysqli_free_result($location_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Class</dt>
                <dd>
                    <select name="class_name">
                        <option value=""></option>
                        <?php
                        $name_set = find_all_class_name();
                        while($name = mysqli_fetch_assoc($name_set)) {
                            echo "<option value=\"" . h($name['class_name']) . "\"";
                            if($vehicle['class_name'] == $name['class_name']) {
                                echo " selected";
                            }
                            echo ">" . h($name['class_name']) . "</option>";
                        }
                        mysqli_free_result($name_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <div id="operations">
                <input type="submit" value="Create Vehicle" />
            </div>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
