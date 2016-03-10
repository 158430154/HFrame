<!DOCTYPE html>
<html>
<head>
    <title><?php echo H::app()->getConfig('app_name'); ?></title>
    <meta charset="utf-8">
    <link rel="icon" href="<?php echo H::app()->public_url; ?>/images/favicon.ico" sizes="any">
    <link href="<?php echo H::app()->public_url; ?>/login/css/login.css" type="text/css" rel="stylesheet">
    <script> window.base_url = '<?php echo H::app()->base_url; ?>'; window.WEB_XHR_POLLING = true; </script>
</head>
<body>
    <?php echo $H_VIEW_HTML; ?>
    <script src="<?php echo H::app()->public_url; ?>/js/jquery.min.js"></script>
    <script src="<?php echo H::app()->public_url; ?>/js/md5.min.js"></script>
    <script src="<?php echo H::app()->public_url; ?>/login/js/login.js"></script>
    <script src="http://cdn.ronghub.com/RongIMLib-2.0.12.min.js"></script>
    <script src="<?php echo H::app()->public_url; ?>/login/js/im.js"></script>
</body>
</html>