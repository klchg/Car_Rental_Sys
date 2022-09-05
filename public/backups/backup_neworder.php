<?php

require_once('../private/initialize.php');

require_login_customer();

if(is_post_request()) {

    $service = [];
    $service['pk_date'] = $_POST['pk_date'] ?? '';
    $service['d_date'] = $_POST['d_date'] ?? '';
    $service['s_odom'] = $_POST['s_odom'] ?? '';
    $service['e_odom'] = $_POST['e_odom'] ?? '';
    $service['daily_limit'] = $_POST['daily_limit'] ?? '';
    $service['vid'] = $_POST['vid'] ?? '';
    $service['pk_l_id'] = $_POST['pk_l_id'] ?? '';
    $service['d_l_id'] = $_POST['d_l_id'] ?? '';
    $service['c_no'] = $_POST['c_no'] ?? '';
    $service['cou_id'] = $_POST['cou_id'] ?? '';

    $result = insert_service($service);
    if($result === true) {
        $new_id = mysqli_insert_id($db);
        $_SESSION['message'] = 'The order was created successfully.';
        redirect_to(url_for('myorders.php'));
    } else {
        $errors = $result;
    }

} else {

    $service = [];
    $service['vid'] = $_GET['vid']??'';
    $service['pk_l_id'] = $_GET['pk_l_id']??'';
    $service['pk_date'] = '';
    $service['d_date'] = '';
    $service['s_odom'] = '';
    $service['e_odom'] = '';
    $service['daily_limit'] = '';
    $service['d_l_id'] = '';
    $service['c_no'] = '';
    $service['cou_id'] = '';
}

?>

<?php $page_title = 'Create Order'; ?>
<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('pagetwo.php'); ?>">&laquo; Back to My Home Page</a>

    <div class="page new">
        <h1>Create Order</h1>

        <?php echo display_errors($errors); ?>

        <form action="<?php echo url_for('neworder.php'); ?>" method="post">
            <input type="hidden" name="vid" value="<?php echo $service['vid']?>"/>
            <input type="hidden" name="pk_l_id" value="<?php echo $service['pk_l_id']?>"/>
            <input type="hidden" name="c_no" value="1"/>

            <dl>
                <dt>pick up date</dt>
                <dd>
                    <label for="pk_date">Select your pick up date:</label>
<!--                    --><?php //$date = date("Y-m-d\TH:i:s", strtotime($result['schedule']));?>
                    <input name="pk_date" type="date" />
                </dd>
            </dl>

            <dl>
                <dt>daily odometer limit</dt>
                <dd>
                    <label for="daily_limit">Select your daily odometer limit:</label>
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
                <dt>Pick up Location</dt>
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
                <dt>Coupon</dt>
                <dd>
                    <label for="coupon">Input your coupon number to get discount:</label>
                    <input name="cou_id" type="text" />
                </dd>
            </dl>

            <div id="create_order">
                <input type="submit" value="Create Order" />
            </div>

<!--        </form>-->
<!--<!--            <dl>-->-->
<!--<!--                <dt>Subject</dt>-->-->
<!--<!--                <dd>-->-->
<!--<!--                    <select name="subject_id">-->-->
<!--<!--                        -->--><?php
////                        $subject_set = find_all_subjects();
////                        while($subject = mysqli_fetch_assoc($subject_set)) {
////                            echo "<option value=\"" . h($subject['id']) . "\"";
////                            if($page["subject_id"] == $subject['id']) {
////                                echo " selected";
////                            }
////                            echo ">" . h($subject['menu_name']) . "</option>";
////                        }
////                        mysqli_free_result($subject_set);
////                        ?>
<!--<!--                    </select>-->-->
<!--<!--                </dd>-->-->
<!--<!--            </dl>-->-->
<!---->
<!--            <table class="list">-->
<!--                <tr>-->
<!--                    <th>VID</th>-->
<!--                    <th>Rental Rate</th>-->
<!--                    <th>Over Fee</th>-->
<!--                    <th>Location ID</th>-->
<!--<!--                    <th>&nbsp;</th>-->-->
<!--<!--                    <th>&nbsp;</th>-->-->
<!--<!--                    <th>&nbsp;</th>-->-->
<!--                </tr>-->
<!---->
<!--            --><?php
//            $available_set = find_vehicles_by_brand_and_city($service['make'],$service['city']);
//            while($available = mysqli_fetch_assoc($available_set)){ ?>
<!---->
<!--                <tr>-->
<!--                    <td>--><?php //echo h($available['vid']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['rental_rate']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['over_fee']); ?><!--</td>-->
<!--                    <td>--><?php //echo h($available['l_id']); ?><!--</td>-->
<!--<!--                    <td><a class="action" href="-->--><?php ////echo url_for('/staff/subjects/show.php?&id=' . h(u($subject['id'])));?><!--<!--">View</a></td>-->-->
<!--<!--                    <td><a class="action" href="-->--><?php ////echo url_for('/staff/subjects/edit.php?id=' . h(u($subject['id'])));?><!--<!--">Edit</a></td>-->-->
<!--<!--                    <td><a class="action" href="-->--><?php ////echo url_for('/staff/subjects/delete.php?id=' . h(u($subject['id'])));?><!--<!--">Delete</a></td>-->-->
<!--                </tr>-->
<!--            --><?php //} ?>
<!---->
<!---->
<!--            <div class="attributes">-->
<!--                <dl>-->
<!--                    <dt>Pick up date</dt>-->
<!--                    <dd>--><?php //echo h($order['pk_date']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Drop date</dt>-->
<!--                    <dd>--><?php //echo h($order['d_date']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Start Odometer</dt>-->
<!--                    <dd>--><?php //echo h($order['s_odom']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>End Odometer</dt>-->
<!--                    <dd>--><?php //echo h($order['e_odom']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Daily Limit</dt>-->
<!--                    <dd>--><?php //echo h($order['daily_limit']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Vehicle ID</dt>-->
<!--                    <dd>--><?php //echo h($order['vid']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Pick up Location ID</dt>-->
<!--                    <dd>--><?php //echo h($order['pk_l_id']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Drop Location ID</dt>-->
<!--                    <dd>--><?php //echo h($order['d_l_id']); ?><!--</dd>-->
<!--                </dl>-->
<!--                <dl>-->
<!--                    <dt>Coupon ID</dt>-->
<!--                    <dd>--><?php //echo h($order['cou_id']); ?><!--</dd>-->
<!--                </dl>-->
<!--            </div>-->
<!---->
<!--            <dl>-->
<!--                <dt>Available Cars</dt>-->
<!--                <dd><input type="text" name="menu_name" value="--><?php //echo h($page['menu_name']); ?><!--" /></dd>-->
<!--            </dl>-->
<!--            <dl>-->
<!--                <dt>Position</dt>-->
<!--                <dd>-->
<!--                    <select name="position">-->
<!--                        --><?php
//                        for($i=1; $i <= $page_count; $i++) {
//                            echo "<option value=\"{$i}\"";
//                            if($page["position"] == $i) {
//                                echo " selected";
//                            }
//                            echo ">{$i}</option>";
//                        }
//                        ?>
<!--                    </select>-->
<!--                </dd>-->
<!--            </dl>-->
<!--            <dl>-->
<!--                <dt>Visible</dt>-->
<!--                <dd>-->
<!--                    <input type="hidden" name="visible" value="0" />-->
<!--                    <input type="checkbox" name="visible" value="1"--><?php //if($page['visible'] == "1") { echo " checked"; } ?><!-- />-->
<!--                </dd>-->
<!--            </dl>-->
<!--            <dl>-->
<!--                <dt>Content</dt>-->
<!--                <dd>-->
<!--                    <textarea name="content" cols="60" rows="10">--><?php //echo h($page['content']); ?><!--</textarea>-->
<!--                </dd>-->
<!--            </dl>-->
<!--            <div id="operations">-->
<!--                <input type="submit" value="Create Page" />-->
<!--            </div>-->
<!--        </form>-->

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
