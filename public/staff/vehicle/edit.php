<?php

require_once('../../../private/initialize.php');

require_login();

if(!isset($_GET['vid'])) {
    redirect_to(url_for('/staff/vehicle/index.php'));
}
$vid = $_GET['vid'];

if(is_post_request()) {

    $vehicle = [];
    $vehicle['vid'] = $vid;
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
//    $result = find_location_by_factors($vehicle);
//    while($location = mysqli_fetch_assoc($result)){
//        $vehicle['l_id'] = $result['c_id'] ?? '';
//    }


    $result = update_vehicle($vehicle);
    if($result === true) {
        $_SESSION['message'] = 'The vehicle was updated successfully.';
        redirect_to(url_for('/staff/vehicle/show.php?vid=' . $vid));
    } else {
        $errors = $result;
    }

} else {

    $vehicle_set = find_vehicle_by_id($vid);

}

?>

<?php $page_title = 'Edit Vehicle'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/vehicle/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="location edit">
        <h1>Edit Vehicle</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('/staff/vehicle/edit.php?vid=' . h(u($vid))); ?>" method="post">
            <?php while($vehicle = mysqli_fetch_assoc($vehicle_set)){ ?>
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
                <input type="submit" value="Edit Vehicle" />
            </div>
            <?php } mysqli_free_result($vehicle_set); ?>
        </form>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
