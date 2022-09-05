<!doctype html>

<html lang="en">
  <head>
    <title>Globe Bank <?php if(isset($page_title)) { echo '- ' . h($page_title); } ?><?php if(isset($preview) && $preview) { echo ' [PREVIEW]'; } ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/public.css'); ?>" />
  </head>

<style>
.btn { position:absolute;left:1000px;top:140px;}
</style>
<style>
.btn2 { position:absolute;left:1130px;top:140px;}
</style>

  <body>

    <header>
      <h1>
        <a href="<?php echo url_for('/pageone.php'); ?>">
          <img src="<?php echo url_for('/images/logo.png'); ?>" width="1018" height="101" alt="" />
        </a>
      </h1>
    </header>
    <a class="btn" href="<?php echo url_for('contact-us.php'); ?>">&laquo; Contact Us</a>
    <a class="btn2" href="<?php echo url_for('pagefaq.php'); ?>">&laquo; FAQs</a>
