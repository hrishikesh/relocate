<?php
/**
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
$cakeDescription = __d('cake_dev', $appName);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $title_for_layout; ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css(array(
        "flatly.bootstrap", 'jquery-ui-1.8.22.custom','jquery-ui','developer'));

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->Html->script(array(
        'jquery_ui/jquery',
        'bootstrap',
        'jquery_ui/jquery-ui',

    ));
    echo $this->fetch('script');
    ?>
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
    </style>

    <script type="text/javascript">
        //<![CDATA[
        jQuery(function () {
            if ($('#flashMessage').length > 0 || $('#authMessage').length > 0) {
                $('#NotifyMessage').slideDown('fast');
                $('#flashMessage').delay(25000).slideUp(function () {
                    $(this).hide();
                });
                $('#authMessage').delay(25000).slideUp();

                $('#NotifyMessage').delay(25000).slideUp();
            } else {
                $('#flashMessage').hide();
                $('#NotifyMessage').hide();
            }
        });
    </script>

</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <?php echo $this->element('siteAdmin/header'); ?>
        </div>
    </div>
</div>
<div class="container">
    <?php
    echo $this->Session->flash();
    echo $this->Session->flash('auth');
    echo $this->fetch('content');
    ?>
    <hr>
    <?php echo $this->element('siteAdmin/footer'); ?>
    <div class="clearfix">
        <?php echo $this->element('sql_dump'); ?>
    </div>
</div>

</body>
</html>