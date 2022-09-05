<?php

require_once('../../../private/initialize.php');

require_login();
if ((!isset($_GET['s_id'])) || empty($_GET['s_id']) || $_GET['s_id'] == '')
    redirect_to(url_for('/staff/service/index.php'));
if ((!isset($_GET['vid'])) || empty($_GET['vid']) || $_GET['vid'] == '')
    redirect_to(url_for('/staff/service/index.php'));
$service = [];
$service['s_id'] = $_GET['s_id'];
$service['vid'] = $_GET['vid'];

if(is_post_request()) {
    $result = delete_service($service);
    if ($result === true) {
        $_SESSION['message'] = 'The order was deleted successfully.';

    } else {
        $errors = $result;
    }
    redirect_to(url_for('/staff/service/index.php'));
}


?>
<?php $page_title = 'View all services'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

    <a class="back-link" href="<?php echo url_for('/staff/service/index.php'); ?>">&laquo; Back to Previous Page</a>

    <div class="coop delete">
    <h1>Delete Order</h1>
        <p>Are you sure you want to delete this order?</p>

    <form action="<?php echo url_for('/staff/service/delete.php?&s_id=' . h(u($service['s_id'])) . '&vid='. h(u($service['vid']))); ?>" method="post" id="yes">
        <input type="hidden" name="s_id" value="<?php echo $service['s_id']?>"/>
        <input type="hidden" name="vid" value="<?php echo $service['vid']?>"/>
    </form>

    <form action="<?php echo url_for('/staff/service/index.php'); ?>" id="no"></form>

    <button type="submit" form="yes" class="btn btn-sm btn-info" name="something"><i class="fa fa-check"></i>Yes</button>
    <button type="submit" form="no" class="btn btn-sm btn-info" name="something"><i class="fa fa-check"></i>No</button>

</div>


<?php include(SHARED_PATH . '/staff_footer.php'); ?>
