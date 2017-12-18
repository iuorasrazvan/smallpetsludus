<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$license = array(
    array(
        "title" => "Button Design",
        "text" => "Make customization and make your button pretty, changing the texts, fonts, colors and more . . .",
        "icon" => "-137px -230px"
    ),
    array(
        "title" => "Google Sign In",
        "text" => "Use the Google Sign In option to give users a chance to use Google account to sign in instead of standard login",
        "icon" => "-134px -300px"
    ),
    array(
        "title" => "Facebook Login",
        "text" => "In addition to the conventional method of registering an account, the users are given the opportunity to enter through their Facebook pages",
        "icon" => "-17px -300px"
    ),
    array(
        "title" => "reCAPTCHA",
        "text" => "Simple in use and very effective function to protect against bots and spam is Google reCaptcha. In the Pro version the reCaptcha widget can be installed in a sign up",
        "icon" => "-370px -230px"
    ),
    array(
        "title" => "Login Menu Access",
        "text" => "In the Professional version of Login plugin, you are also able to work with the account menu. As soon as you enter in, you see My Account and Logout menus, which have a default link to My account and Logout button",
        "icon" => "-16px -230px"
    ),
    array(
        "title" => "Pop-Up Options",
        "text" => "Professional version of the plugin provides more than 25 options for design available in the Design section of the plugin, which will help to match the style of buttons and popup window",
        "icon" => "-265px -230px"
    )
);
?>
<div class="responsive grid">
    <?php foreach ($license as $key => $val) { ?>
        <div class="col column_1_of_3">
            <div class="header">
                <div class="col-icon" style="background-position: <?= $val["icon"] ?>; ">
                </div>
                <?= $val["title"] ?>
            </div>
            <p><?= $val["text"] ?></p>
            <div class="col-footer">
                <a href="http://huge-it.com/wordpress-login/" class="a-upgrate">Upgrade</a>
            </div>
        </div>
    <?php } ?>
</div>
<div class="license-footer">
    <p class="footer-text">
        You are using the Lite version of the Login Plugin for WordPress. If you want to get more awesome options,
        advanced features, settings to customize every area of the plugin, then check out the Full License plugin.
        The full version of the plugin is available in 3 different packages of one-time payment.
    </p>
    <p class="this-steps max-width">
        After the purchasing the commercial version follow this steps
    </p>
    <ul class="steps">
        <li>Deactivate Huge IT Login Plugin</li>
        <li>Delete Huge IT Login</li>
        <li>Install the downloaded commercial version of the plugin</li>
    </ul>
    <a href="http://huge-it.com/wordpress-login/" target="_blank">Purchase a License</a>
</div>