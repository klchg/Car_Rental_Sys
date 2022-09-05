<?php

require_once('../../../private/initialize.php');

require_login();

if(is_post_request()) {

    $indiv = [];
    $indiv['i_fname'] = $_POST['i_fname'] ?? '';
    $indiv['i_lname'] = $_POST['i_lname'] ?? '';
    $indiv['dl_no'] = $_POST['dl_no'] ?? '';
    $indiv['c_email'] = $_POST['c_email'] ?? '';
    $indiv['p_no'] = $_POST['p_no'] ?? '';

    $result = find_indiv_by_factors($indiv);
    $available_set = $result[0];
    $errors = $result[1];

} else {

    $indiv = [];
    $indiv['i_fname'] = '';
    $indiv['i_lname'] = '';
    $indiv['dl_no'] = '';
    $indiv['c_email'] = '';
    $indiv['p_no'] = '';
    $available_set = [];

}

?>

<?php $page_title = 'Individual Customer Search'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
    <a class="back-link" href="<?php echo url_for('/staff/indiv/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="individual customer search">
        <h1>Search Individual Customer</h1>

        <?php if(!is_null($errors)){echo display_errors($errors);} ?>

        <form action="<?php echo url_for('/staff/indiv/search.php'); ?>" method="post">
            <dl>
                <dt>Firstname</dt>
                <dd>
                    <input name="i_fname" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Lastname</dt>
                <dd>
                    <input name="i_lname" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Driver License Number</dt>
                <dd>
                    <input name="dl_no" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Email Address</dt>
                <dd>
                    <input name="c_email" type="text" />
                </dd>
            </dl>

            <dl>
                <dt>Phone Number</dt>
                <dd>
                    <input name="p_no" type="text" />
                </dd>
            </dl>

            <div id="operations">
                <input type="submit" value="Search" />
            </div>

        </form>

        <?php if($available_set!=[]){?>
            <table class="list">
                <tr>
                    <th>Customer Firstname</th>
                    <th>Customer Lastname</th>
                    <th>Driver License Number</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>&nbsp;</th>
                </tr>

                <?php
                while($available = mysqli_fetch_assoc($available_set)){ ?>

                    <tr>
                        <td><?php echo h($available['i_fname']); ?></td>
                        <td><?php echo h($available['i_lname']); ?></td>
                        <td><?php echo h($available['dl_no']); ?></td>
                        <td><?php echo h($available['c_email']); ?></td>
                        <td><?php echo h($available['p_no']); ?></td>
                        <td><a class="action" href="<?php echo url_for('/staff/indiv/show.php?c_no=' . h(u($available['c_no']))); ?>">View</a></td>
<!--                        <td><a class="action" href="--><?php //echo url_for('/staff/coop/edit.php?coop_id=' . h(u($available['coop_id'])));?><!--">Edit</a></td>-->
<!--                        <td><a class="action" href="--><?php //echo url_for('/staff/coop/delete.php?&coop_id=' . h(u($available['coop_id'])));?><!--">Delete</a></td>-->
                    </tr>
                <?php } ?>
            </table>

            <?php mysqli_free_result($available_set); }?>

    </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
