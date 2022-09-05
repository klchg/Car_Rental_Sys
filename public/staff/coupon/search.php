<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $coupon = [];
    $coupon['cou_discount'] = $_POST['cou_discount'] ?? '';
    $coupon['s_date'] = $_POST['s_date'] ?? '';
    $coupon['e_date'] = $_POST['e_date']?? '';
    $coupon['cou_no'] = $_POST['cou_no'] ?? '';
    $coupon['is_available'] = $_POST['is_available'] ?? '';

    $result = find_coupon_by_factors($coupon);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $coupon = [];
    $coupon['cou_discount'] = '';
    $coupon['s_date'] = '';
    $coupon['e_date'] = '';
    $coupon['cou_no'] = '';
    $coupon['is_available'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Coupon Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/coupon/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="coupon search">
        <h1>Search coupon</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/coupon/search.php'); ?>" method="post">
            <dl>
                <dt>Coupon Discount</dt>
                <dd>
                    <label for="cou_discount">Input discount range from 1 to 100:</label>
                    <input name="cou_discount" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Coupon Start date</dt>
                <dd>
                    <input name="s_date" type="date" />
                </dd>
            </dl>

            <dl>
                <dt>Coupon End date</dt>
                <dd>
                    <input name="e_date" type="date" />
                </dd>
            </dl>

            <dl>
                <dt>Coupon Number</dt>
                <dd>
                    <input name="cou_no" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Availability</dt>
                <dd>
                    <select name="is_available">
                        <option></option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
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
                    <th>Coupon Discount</th>
                    <th>Coupon Start date</th>
                    <th>Coupon End date</th>
                    <th>Coupon Number</th>
                    <th>Availability</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['cou_discount']); ?></td>
                        <td><?php echo substr(h($available['s_date']), 0, 10); ?></td>
                        <td><?php echo substr(h($available['e_date']), 0, 10) ?></td>
                        <td><?php echo h($available['cou_no']); ?></td>
                        <td><?php echo h($available['is_available']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/coupon/edit.php?cou_id=' . h(u($available['cou_id'])));?>">Edit</a></td>
                        <td><a class="action" href="<?php echo url_for('/staff/coupon/delete.php?&cou_id=' . h(u($available['cou_id'])));?>">Delete</a></td>
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
