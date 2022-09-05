<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $service = [];
    $service['username'] = $_POST['username'] ?? '';
    $service['vin'] = $_POST['vin'] ?? '';
    $result = find_service_by_vin_username($service);
    $available = $result[0];
    $errors = $result[1];

} else {

    $service = [];
    $service['username'] = '';
    $service['vin'] = '';

    $available = [];


}

?>

<?php $page_title = 'Service Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Previous Page</a>

    <?php if(!is_null($errors)){echo display_errors($errors);} ?>

    <div class="coupon search">
        <h1>Search Service</h1>

        <form action="<?php echo url_for('/staff/finish_order/search.php'); ?>" method="post">
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

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available!=[]){?>
            <table class="list">
                <tr>
                    <th>Brand</th>
                    <th>Pick up Date</th>
                    <th>Daily Limit</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <tr>
                    <td><?php echo h($available['make']); ?></td>
                    <td><?php echo substr(h($available['pk_date']), 0, 10); ?></td>
                    <td><?php echo h($available['daily_limit']); ?></td>
                    <td><a class="action" href="<?php echo url_for('/staff/finish_order/show.php?&s_id='. h(u($available['s_id'])));?>">View&nbsp;</a></td>
                    <td><a class="action" href="<?php echo url_for('/staff/finish_order/finish.php?&username='. h(u($available['username'])) . '&vin=' . h(u($available['vin'])) );?>">Finish&nbsp;</a></td>
                </tr>
            </table>
            <?php }?>


    </div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>