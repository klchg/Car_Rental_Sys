<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $location = [];
    $location['l_street'] = $_POST['l_street'] ?? '';
    $location['l_city'] = $_POST['l_city'] ?? '';
    $location['l_state'] = $_POST['l_state'] ?? '';
    $location['l_zipcode'] = $_POST['l_zipcode'] ?? '';
    $location['l_pno'] = $_POST['l_pno'] ?? '';

    $result = find_location_by_factors($location);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $location = [];
    $location['l_street'] = '';
    $location['l_city'] = '';
    $location['l_state'] = '';
    $location['l_zipcode'] = '';
    $location['l_pno'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Location Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/location/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="class search">
        <h1>Search Locations</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/location/search.php'); ?>" method="post">
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
                            if($location['l_state'] == $state['l_state']) {
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
                            if($location['l_city'] == $city['l_city']) {
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
                            if($location['l_street'] == $street['l_street']) {
                                echo " selected";
                            }
                            echo ">" . h($street['l_street']) . "</option>";
                        }
                        mysqli_free_result($street_set);
                        ?>
                    </select>
                </dd>
            </dl>
            <dl>
                <dt>Phone Number</dt>
                <dd>
                    <input name="l_pno" type="text" />
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Street</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Zipcode</th>
                    <th>Phone Number</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['l_street']); ?></td>
                        <td><?php echo h($available['l_city']); ?></td>
                        <td><?php echo h($available['l_state']); ?></td>
                        <td><?php echo h($available['l_zipcode']); ?></td>
                        <td><?php echo h($available['l_pno']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/location/edit.php?l_id=' . h(u($available['l_id'])));?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/location/delete.php?&l_id=' . h(u($available['l_id'])));?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
