<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $service = [];
    $service['username'] = $_POST['username'] ?? '';
    $service['vin'] = $_POST['vin'] ?? '';
    $service['make'] = $_POST['make'] ?? '';
    $service['class_name'] = $_POST['class_name'] ?? '';

    //    $service = mysqli_fetch_assoc($result); // find first
//
//    if(mysqli_num_rows($result)<=0) {
//      $errors[] = "No result.";
//    }
//    mysqli_free_result($result);

    $result = find_services_by_factors($service);
    $order_set = $result[0];
    $errors = $result[1];

//    $order_set = find_all_orders();
    $going_set = [];
    $unstarted_orders =[];

} else {

    $service = [];
    $service['username'] = '';
    $service['vin'] = '';
    $service['make'] = '';
    $service['class_name'] = '';

    $order_set = '';
    $going_set = [];
    $unstarted_orders =[];


}

?>

<?php $page_title = 'Service Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/service/index.php'); ?>">&laquo; Back to Previous Page</a>

    <?php if(!is_null($errors)){echo display_errors($errors);} ?>

    <div class="coupon search">
        <h1>Search Service</h1>

        <form action="<?php echo url_for('/staff/service/search.php'); ?>" method="post">
            <dl>
                <dt>Customer Username:</dt>
                <dd>
                    <input name="username" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>VIN:</dt>
                <dd>
                    <input name="vin" type="text" />
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
                            if($service['make'] == $brand['make']) {
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
                <dt>Class</dt>
                <dd>
                    <select name="class_name">
                        <option value=""></option>
                        <?php
                        $class_set = find_all_class_name();
                        while($class = mysqli_fetch_assoc($class_set)) {
                            echo "<option value=\"" . h($class['class_name']) . "\"";
                            if($service['class_name'] == $class['class_name']) {
                                echo " selected";
                            }
                            echo ">" . h($class['class_name']) . "</option>";
                        }
                        mysqli_free_result($class_set);
                        ?>
                    </select>
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if( $_SERVER['REQUEST_METHOD'] != 'GET'){?>
        <h4>Completed Orders</h4>
        <?php if(mysqli_num_rows($order_set)>0){?>
            <table class="list">
                <tr>
                    <th>Pick up Date</th>
                    <th>Drop Date</th>
                    <th>Daily Limit</th>
                    <th>Username</th>
                    <th>&nbsp;</th>
                </tr>

                <?php while($order = mysqli_fetch_assoc($order_set)){
                    if (!isset($order['is_complete']) || $order['is_complete'] == 0) {
                        array_push($going_set, $order);
                    } else {
                ?>

                <tr>
                    <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                    <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                    <td><?php echo h($order['daily_limit']); ?></td>
                    <td><?php echo h($order['username']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
                </tr>
                    <?php }} ?>
            </table>

        <?php mysqli_free_result($order_set); }?>



        <?php if($going_set!=[]){?>
            <h4>Going Orders</h4>
            <table class="list">
                <tr>
                    <th>Pick up Date</th>
                    <th>Drop Date</th>
                    <th>Daily Limit</th>
                    <th>Username</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                foreach($going_set as $key => $order){
                    if (!isset($order['pk_date']) || empty($order['pk_date']) ||$order['pk_date'] == '' || strtotime($order['pk_date']) > time()) {
                        array_push($unstarted_orders, $order);
                    } else {
                        ?>

                        <tr>
                            <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                            <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                            <td><?php echo h($order['daily_limit']); ?></td>
                            <td><?php echo h($order['username']); ?></td>
                            <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
                            <!--                        <td><a class="action" href="--><?php //echo url_for('/staff/service/edit.php?&s_id=' . h(u($order['s_id'])));?><!--">Edit&nbsp;</a></td>-->
                        </tr>
                    <?php }} ?>
            </table>
        <?php } ?>


        <?php if($unstarted_orders!=[]){?>
            <h4>Unstarted Orders</h4>
            <table class="list">
                <tr>
                    <th>Pick up Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Drop Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>Daily Limit &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</th>
                    <th>Username &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                foreach($unstarted_orders as $key => $order){ ?>

                    <tr>
                        <td><?php echo substr(h($order['pk_date']), 0, 10); ?></td>
                        <td><?php echo substr(h($order['d_date']), 0, 10); ?></td>
                        <td><?php echo h($order['daily_limit']); ?></td>
                        <td><?php echo h($order['username']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/service/show.php?&s_id=' . h(u($order['s_id'])));?>">View</a></td>
                        <!--                    <td><a class="action" href="--><?php //echo url_for('/staff/service/edit.php?&s_id=' . h(u($order['s_id'])));?><!--">Edit&nbsp;</a></td>-->
                        <td><a class="action" href="<?php echo url_for('/staff/service/delete.php?&s_id=' . h(u($order['s_id'])) . '&vid='. h(u($order['vid'])));?>"> &nbsp; Delete</a></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
        <?php }?>

    </div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>