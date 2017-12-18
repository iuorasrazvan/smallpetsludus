<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

final class WPUserShortcode
{

    public function __construct()
    {
        add_shortcode('wp_user', array($this, 'wp_user'));
        add_shortcode('wp_user_member', array($this, 'wp_user_member'));
        add_shortcode('wp_user_restrict', array($this, 'wp_user_restrict'));
    }

function wp_user($atts)
{
    $form_id = time() . rand(2, 999);
    $login_redirect = "";
    $wp_user_form_width = (isset($atts['width']) && !empty($atts['width']) && !is_user_logged_in()) ? $atts['width'] : '100%';
    $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
        (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');

    include_once('includes/assets.php');
    include_once('view/appearance.php');
    do_action('wp_user_member',$atts);

    ob_start();
    echo '<style>';
    echo '.skin_rounded {
                height: 32px;
                width: 32px;
                background: #f5f5f5 !important;
                color: #999 !important;
                border: 1px transparent;
                padding: 7px;
                border-radius: 50%;
            }
            .rounded input {
            border-radius: 3px !important;
            }
            .rounded:hover + .skin_rounded {
                color: black !important;
            }
            .bootstrap-wrapper .model{
            position: fixed !important;
            z-index: 999999999 !important;
            }
            
           /* .wpuser-custom-box, .wpuser-custom-nav li.active{
            border-top-color :red !important;
            }
            .wpuser-custom-button{
                background-color: red !important;
                color: #ffffff;
                border-color: #a1300d;
            }
            .wpuser-custom-header-nav, .wpuser-custom-header-user, li.user-header.bg-light-' . $wp_user_appearance_skin_color . '{
            background-color: red !important;
            }
            .bootstrap-wrapper .wpuser-custom-progress-bar {
            background-color: red !important;
            }*/
            .close_model{
                margin-right: -10px !important;
                margin-top: -10px !important;
                background: white !important;
                border-radius: 50% !important;
                width: 22px !important;
                opacity: 1 !important;
                }
                .wpuser_login .box {
                border-radius: 0px 25px !important;
                }
            ';

    echo get_option('wp_user_appearance_custom_css');
    echo '</style>';
    echo '<div style="max-width:' . $wp_user_form_width . '" class="bootstrap-wrapper wp_user support_bs">';

    if (isset($atts['login_redirect'])) {
        $login_redirect = $atts['login_redirect'];
    } else {
        // $login_redirect=get_permalink(get_option('wp_user_page'));
    }

    $login_class = '';
    $register_class = '';
    $forgot_class = '';
    if (isset($atts['active']) && $atts['active'] == 'register') {
        $register_class = 'active';
    } else if (isset($atts['active']) && $atts['active'] == 'forgot') {
        $forgot_class = 'active';
    } else {
        $login_class = 'active';
    }

if (isset($atts['popup']) && $atts['popup'] == 1)
{
    $form_id = $form_id . 'p';
if (is_user_logged_in())
{
    echo '<a href="' . wp_logout_url(get_permalink()) . '" title="">';
    _e('Logout', 'wpuser');
    echo '</a>';
} else
{
    ?>
    <div ng-app="listpp" ng-app lang="en">
        <!-- Button trigger modal -->
        <a id="wp_login_btn<?php echo $form_id ?>">
            <?php if (isset($atts['active']) && $atts['active'] == 'register') {
                _e('Sign Up', 'wpuser');
            } else {
                _e('Sign In', 'wpuser');
            } ?>
        </a>
        <!-- Modal -->
        <div style="margin:auto;overflow: scroll" class="modal fade wpuser_login" role="dialog"
             id="wp_login<?php echo $form_id ?>"
             tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

            <div
                style="z-index:1;margin:auto; max-width:<?php echo (isset($atts['width']) && !empty($atts['width']) && !is_user_logged_in()) ? $atts['width'] : '900px'; ?>;"
                class="modal-dialog" role="document">
                <div class="modal-content">
                    <div style="padding: 0px;" class="modal-body">
                        <button type="button" class="close close_model" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <?php
                        include('view/view.php');
                        include('includes/script.php');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    } else if (is_user_logged_in()) {
        $wp_user_profile['my_account'] = array(
            'class' => 'WPUserMyProfile',
            'function' => 'my_account',
            'tab' => 'My Acount',
            'icon' => 'glyphicon glyphicon-dashboard',
            'order' => '0',
            'parent' => '0',
            'active' => 'active'
        );
        $wp_user_profile['edit_profile'] = array(
            'class' => 'WPUserMyProfile',
            'function' => 'edit_profile',
            'tab' => 'Edit Profile',
            'icon' => 'glyphicon glyphicon-edit',
            'order' => '0',
            'parent' => '0',
            'active' => ''
        );

        $wp_user_profile = apply_filters('wpuser_multiple_address_list', $wp_user_profile);

        if (get_option('wp_user_disable_contact_form_myprofile') != 1) {
            $wp_user_profile['contact_us'] = array(
                'class' => 'WPUserMyProfile',
                'function' => 'contact_us',
                'tab' => 'Contact Us',
                'icon' => 'glyphicon glyphicon-envelope',
                'order' => '0',
                'parent' => '0',
                'active' => ''
            );
        }
        
        //echo '<a href="' . wp_logout_url(get_permalink()) . '" title="">';
        include('view/profile.php');
        //echo '</a>';
    } else {
        include('view/view.php');
        include('includes/script.php');

    }
    echo '</div>';
    return ob_get_clean();
    }

    function wp_user_member($atts)
    {

    wp_enqueue_script('jquery');
    //jPList lib
    wp_enqueue_script('wpdbbootstrap', WPUSER_PLUGIN_URL . "assets/js/bootstrap.min.js");
    wp_enqueue_script('wpdbbootstraprecaptcha', "https://www.google.com/recaptcha/api.js");

    wp_enqueue_script('wpuserjplist', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.core.min.js");
    wp_enqueue_script('wpuserjplistbootstrap', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-filter-dropdown.min.js");
    wp_enqueue_script('wpuserapppagination', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-pagination-bundle.min.js");
    wp_enqueue_script('wpusersortdropdown', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.bootstrap-sort-dropdown.min.js");
    wp_enqueue_script('wpusersortfilter', WPUSER_PLUGIN_URL . "assets/js/jplist/jplist.textbox-filter.min.js");

    wp_enqueue_style('wpdbbootstrapcss', WPUSER_PLUGIN_URL . "assets/css/bootstrap.min.css");
    wp_enqueue_style('wpdbbootstrapcdncss', WPUSER_PLUGIN_URL . "assets/css/font-awesome.min.css");
    wp_enqueue_style('wpdbadminltecss', WPUSER_PLUGIN_URL . "assets/dist/css/AdminLTE.css");
    wp_enqueue_style('wpdbbskinscss', WPUSER_PLUGIN_URL . "assets/dist/css/skins/_all-skins.min.css");
    wp_enqueue_style('wpdbiCheckcss', WPUSER_PLUGIN_URL . "assets/plugins/iCheck/flat/blue.css");

    $role__in = (isset($atts['role_in']) && !empty($atts['role_in'])) ? explode(',', $atts['role_in']) : array();
    $role__not_in = (isset($atts['role_not_in']) && !empty($atts['role_not_in'])) ? explode(',', $atts['role_not_in']) : array();
    $include = (isset($atts['include']) && !empty($atts['include'])) ? explode(',', $atts['include']) : array();
    $exclude = (isset($atts['exclude']) && !empty($atts['exclude'])) ? explode(',', $atts['exclude']) : array();
    $meta_key = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 'wp-approve-user' : '';
    $meta_value = (isset($atts['approve']) && ($atts['approve'] == '1')) ? 1 : '';
    include_once('view/appearance.php');

    ob_start();
    $args = array(
        'role' => '',
        'role__in' => $role__in,
        'role__not_in' => $role__not_in,
        'meta_key' => $meta_key,
        'meta_value' => $meta_value,
        'meta_compare' => '',
        'meta_query' => array(),
        'date_query' => array(),
        'include' => $include,
        'exclude' => $exclude,
        'offset' => '',
        'search' => '',
        'number' => '',
        'count_total' => false,
        'fields' => 'all',
    );

    $blogusers = get_users($args);
    $wp_user_appearance_skin_color = (isset($atts['skin']) && !empty($atts['skin'])) ? $atts['skin'] :
        (get_option('wp_user_appearance_skin_color') ? get_option('wp_user_appearance_skin_color') : 'blue');

    echo '<div class="bootstrap-wrapper hold-transition skin-' . $wp_user_appearance_skin_color . ' sidebar-mini">';
    do_action('wp_user_member',$args);
    ?><!-- Modal -->
    <div class="modal fade" style="overflow: scroll;margin: auto" id="wpuser_myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel">
        <div class="modal-dialog" style="margin:auto;max-width:700px;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        <?php _e('Send Message to', 'wpuser'); ?>
                        <span id="wpuser_mail_to_name"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <form method="post" id="google_form">
                        <div style="display: none;" id="wpuser_errordiv_send_mail"
                             class="alert alert-dismissible" role="alert"><label
                                id="wpuser_errordiv_send_mail"></label></div>
                        <input name="wpuser_update_setting" type="hidden"
                               value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>
                        <input type="hidden" class="form-control" name="id" class="wpuser_mail_to_userid" value=""
                               id="wpuser_mail_to_userid">
                        <div class="form-group">
                            <label><?php _e('From', 'wpuser'); ?></label>
                            <input type="text" class="form-control" name="from"
                                   placeholder="<?php _e('Email', 'wpuser'); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php _e('Subject', 'wpuser'); ?></label>
                            <input type="text" class="form-control" name="subject"
                                   placeholder="<?php _e('Subject', 'wpuser'); ?>">
                        </div>
                        <div class="form-group">
                            <label><?php _e('Message', 'wpuser'); ?></label>
                            <textarea class="form-control" rows="3"
                                      name="message" placeholder="<?php _e('Message', 'wpuser'); ?>"></textarea>
                        </div>
                        <?php if (get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))) { ?>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div id="recaptcha" class="g-recaptcha"
                                         data-sitekey="<?php echo get_option('wp_user_security_reCaptcha_secretkey') ?>"></div>
                                    <input type="hidden" title="Please verify this" class="required" name="keycode"
                                           id="keycode">
                                </div>
                            </div>
                        <?php } ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn <?php echo $wp_user_appearance_button_type ?> btn-default"
                            data-dismiss="modal">
                        <?php _e('Close', 'wpuser'); ?>
                    </button>
                    <button type="button" id="wpuser_send_mail"
                            class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-primary wpuser-custom-button">
                        <?php _e('Send', 'wpuser'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
    echo '<div class="wpuser_member_profile" id="wpuser_member_profile">';
    echo '<div class="row">
              <div class="col-md-12">
            <button type="button" id="member_list_button" class="pull-right btn btn-default btn-flat">            
            <i class="fa fa-fw fa-users"></i>';
    _e('Member List', 'wpuser');
    echo '</button>
                </div>
                </div>';
    ?>
    <div class="box box-primary wpuser-custom-box col-md-12">
        <div class="box-body box-profile" style="padding:0px">
            <div id="wpuser_member_header" class="wpuser-member-header">
                <img id="wpuser_profile_image" class="profile-user-img img-responsive img-circle" src=""
                     alt="User profile picture">

                <h3 class="profile-username text-center wpuser_profile_name" id="wpuser_profile_name"></h3>

                <p class="text-muted text-center" id="wpuser_profile_title"></p>
                <h3 class="text-center wpuser_profile_badge" id="wpuser_profile_badge">
                </h3>
                <input type="hidden" class="wpuser_mail_to_userid" value="" id="wpuser_profile_id" name="user_id">
                
                        
                <center>
                    <div class="input-group">
                        <button type="button"
                                class="wpuser_button btn <?php echo $wp_user_appearance_button_type ?> btn-default pull-left wpuser_sendmail"
                                id="sendmail">
                    <span>
                      <i class="fa fa-envelope"></i> <?php _e('Send Mail', 'wpuser') ?>
                    </span>

                        </button> &nbsp;&nbsp;
                        <?php do_action('wpuser_member_profile_view', $atts); ?>
                    </div>
                   
                </center>

            </div>
            <br>
            <?php
            if (get_option('wp_user_disable_member_profile_progress') != 1) {
                do_action('wpuser_member_profile_progress', $atts);
            }
            ?>
            <!-- <div class="progress">
                 <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="75"
                      aria-valuemin="0" aria-valuemax="100" style="width:75%">
                     75% Complete
                 </div>
             </div> -->
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="wpuser_user_header">
                    </div>

                </div>
            </nav>

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="wpuser_profile_name panel-title"></h3>
                </div>
                <div class="panel-body">
                    <div class="row">

                        <div class=" col-md-12 col-lg-12 ">
                            <table class="table table-user-information">
                                <tbody class="wpuser_user_info">

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.box-body -->
    </div>
    <?php

    echo '</div>';
    ?>
    <style>
        #wpuser_member_profile {
            display: none;
        }

        .profile-user-img {
            margin: 0 auto;
            width: 76px;
            padding: 3px;
            border: 3px solid #d2d6de;
        }

        .fontfollow {
            font-size: 15px !important;
        }

        .member_list_display_name {
            cursor: pointer;
        }
    </style>
    <script>
        var $ = jQuery.noConflict();
        $('document').ready(function () {

            $('#demo').jplist({
                itemsBox: '.list'
                , itemPath: '.list-item'
                , panelPath: '.jplist-panel'
            });

            $('#login_log').jplist({
                itemsBox: '.list-login'
                , itemPath: '.list-item-login'
                , panelPath: '.jplist-panel-login'
            });

            $("#member_list_button").click(function () {
                $("#wpuser_member_list").css("display", "block");
                $("#wpuser_member_profile").css("display", "none");
            });

        });

        function sendMail(id, name) {
            $("#wpuser_mail_to_userid").val(id);
            $("#wpuser_mail_to_name").html(name);
            $("#wpuser_myModal").modal();
            var modal = $("#wpuser_myModal"),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');
            // Dividing by two centers the modal exactly, but dividing by three
            // or four works better for larger screens.
           // dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
        }

        $(".wpuser_sendmail").click(function () {
            $("#wpuser_myModal").modal();
            var modal = $("#wpuser_myModal"),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');
            // Dividing by two centers the modal exactly, but dividing by three
            // or four works better for larger screens.
           // dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
        });


        function viewProfile(id) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php') ?>?action=wpuser_user_details',
                data: 'id=' + id + '&wpuser_update_setting=<?php echo wp_create_nonce('wpuser-update-setting') ?>',
                success: function (response) {
                    if (response.status == 0)
                        $("#response_message").html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><h4><i class="icon fa fa-ban"></i> <?php _e('Error', 'wpuser'); ?>!</h4>' + response.message + '</div>');
                    else if (response.status == 1) {
                        $("#wpuser_member_list").css("display", "none");
                        $("#wpuser_member_profile").css("display", "block");
                        $(".wpuser_profile_name").html(response.name);
                        $("#wpuser_mail_to_name").html(response.name);
                        $("#wpuser_profile_title").html(response.labels);
                        $(".wpuser_mail_to_userid").val(response.id);
                        $("#wpuser_mail_to_userid").val(response.id);
                        $('#wpuser_profile_image').attr('src', response.wp_user_profile_img);

                        var user_row = '';
                        $.each(response.user_info, function (i, val) {
                            if(i=='wpuser_profile_strength') {
                                $('.wpuser_profile_strength').html(val + '%');
                                $('.wpuser_profile_strength').css("width",val+ '%');
                            }else{
                                user_row = user_row + '<tr class="user_info"><td>' + i + '</td><td>' + val + '</td></tr>';
                            }
                        });
                        $(".wpuser_user_info").html(user_row);
                        var user_header = '';                        
                        $.each(response.header_block_info, function (ar, arval) {
                            var header_attr= ' ';
                            var header_onclick =' ';
                            if(arval["url"]!='#'){
                                header_attr='target="_blank" href="' + arval["url"] + '"';
                            }
                            if(arval["id"]=='wpuser_profile_follower' || arval["id"]=='wpuser_profile_following'){
                                header_onclick ='onclick="getFollower(\''+arval["type"]+'\')"';
                            }
                            user_header = user_header + '<div class="navbar-header"><a class="navbar-brand fontfollow"  '+header_attr+' style="margin:0px;"'+header_onclick+' ><i class="' + arval["icon"] + '"> ' + arval["name"] + '(' + arval["count"] + ')</i></a> </div>';
                        });
                        $(".wpuser_user_header").html(user_header);
                        $("#wpuser_member_header").css("background-image", 'url("' + response.wp_user_background_img + '")');
                        $("#profile_follow_button").html(response.user_header_follow_button);
                        $("#wpuser_profile_badge").html(response.user_badge);
                    }
                }
            });
            $('#wpuser_followModal').modal('hide');
        }
        $("#wpuser_send_mail").click(function () {
            <?php if(get_option('wp_user_security_reCaptcha_enable') && !empty(get_option('wp_user_security_reCaptcha_secretkey'))){ ?>

            if (grecaptcha.getResponse() == '') {
                $('#wpuser_errordiv_send_mail').html("Please verify Captcha");
                $('#wpuser_errordiv_send_mail').removeClass().addClass('alert alert-dismissible alert-warning');
                $('#wpuser_errordiv_send_mail').show();
                return false;
            }
            <?php } ?>
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php')?>?action=wpuser_send_mail_action',
                data: $("#google_form").serialize(),
                error: function (data) {
                },
                success: function (data) {
                    var parsed = $.parseJSON(data);
                    $('#wpuser_errordiv_send_mail').html(parsed.message);
                    $('#wpuser_errordiv_send_mail').removeClass().addClass('alert alert-dismissible alert-' + parsed.status);
                    if (parsed.status == 'success') {
                        $("#google_form")[0].reset();
                    }
                    $('#wpuser_errordiv_send_mail').show();
                },
                type: 'POST'
            });
        });


    </script>
    <?php echo '<div class="wpuser_member_list" id="wpuser_member_list">'; ?>
    <div class="row">
        <div class="col-md-12">
            <!-- main content -->
            <form action="" name="wpuser_bulk_action_form"
                  id="wpuser_bulk_action_form"
                  method="post">
                <input name="wpuser_update_setting" type="hidden"
                       value="<?php echo wp_create_nonce('wpuser-update-setting'); ?>"/>

                <div class="page" id="demo">
                    <!-- jplist top panel -->
                    <div class="jplist-panel">
                        <div class="center-block1">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="default form-group has-feedback">
                                        <input
                                            class="form-control"
                                            data-path="*"
                                            type="text"
                                            value=""
                                            placeholder="<?php _e('Search', 'wpuser') ?>"
                                            data-control-type="textbox"
                                            data-control-name="title-filter"
                                            data-control-action="filter"
                                        />
                                        <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row list">
                        <?php
                        $count = 0;
                        echo '<div class="row">';
                        $chunk = (isset($atts['size']) && $atts['size'] == 'small') ? 3 : 2;
                          foreach (array_chunk($blogusers, $chunk) as $chunk_list) {
                              echo '<div class="col-md-12">';
                              foreach ($chunk_list as $value) {
                                  $info['atts'] = $atts;
                                  $info['value'] = $value;
                                  $title = (get_user_meta($value->ID, 'user_title', true));
                                  $user_status = (get_user_meta($value->ID, 'wp-approve-user', true));
                                  // retrieve the thumbnail size of our image
                                  $attachment_url = esc_url(get_the_author_meta('user_meta_image', $value->ID));
                                  $attachment_id = profileController::get_attachment_image_by_url($attachment_url);
                                  // retrieve the thumbnail size of our image
                                  $image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                                  // return the image thumbnail
                                  if (!empty($image_thumb[0])) {
                                      $wp_user_profile_img = $image_thumb[0];
                                  } else if (!empty($attachment_url)) {
                                      $wp_user_profile_img = $attachment_url;
                                  } else {
                                      $args = get_avatar_data($value->ID);
                                      if (!empty($args['url']))
                                          $wp_user_profile_img = $args['url'];
                                      else
                                          $wp_user_profile_img = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
                                  }
                                  $name = get_the_author_meta('first_name', $value->ID) . " " . get_the_author_meta('last_name', $value->ID);
                                  $user_mobile = get_the_author_meta('user_mobile', $value->ID);
                                  $authors_posts = get_posts(array('author' => $value->ID, 'post_status' => 'publish'));
                                  $user_blog_url = (count($authors_posts)) ? get_author_posts_url($value->ID) : '';
                                  if (empty(str_replace(' ', '', $name))) {
                                      $user_info = get_userdata($value->ID);
                                      $name = $user_info->display_name;
                                      if (empty($name)) {
                                          $name = $user_info->user_nicename;
                                      }
                                      if (empty($name)) {
                                          $name = $user_info->user_login;
                                      }
                                  }
                                  $value->user_name = $name;
                                  $grid_class = (isset($atts['size']) && $atts['size'] == 'small') ? 4 : 6;
                                  $class = ($count & 1) ? 'list-odd' : 'list-even';
                                  echo '<div class="col-md-' . $grid_class . ' list-item ' . $class . '" id="user_' . $value->ID . '">';

                                  if (isset($atts['size']) && $atts['size'] == 'small') {
                                      echo '
                                                <div class="box box-primary wpuser-custom-box">
                                                    <div class="box-body box-profile" style="padding:0px !important">

                                                        <div style="margin: 10px;"  class="media-left pos-rel col-md-3">
                                                            <a> <img class="img-circle img-xs" src="' . $wp_user_profile_img . '" width="40px" alt="Profile Picture"></a>
                                                            <i class="badge badge-success badge-stat badge-icon pull-left"></i>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="pull-left"><h5 class="member_list_display_name mar-no"><a onclick="viewProfile(\'' . $value->ID . '\')">' . $name . '</a></h5>
                                                                <small class="text-muted">' . $title . '</small>
                                                            </div>
                                                            <div class="pull-right" style="margin-top: 10px; margin-right: 10px;">';

                                      do_action('wp_user_hook_member_list_button', $info);
                                      echo '</div>
                                                        </div>

                                                    </div>
                                                </div>';
                                  } else {

                                      echo '<div class="box box-primary wpuser-custom-box">
                        <div class="box-body box-profile">
                            <div class="media-left pos-rel col-md-3">
                                <a> <img class="img-circle img-xs" src="' . $wp_user_profile_img . '" width="70px" alt="Profile Picture"></a>
                                <i class="badge badge-success badge-stat badge-icon pull-left"></i>
                            </div>
                            <div class="media-body">
                                <h3 class="member_list_display_name mar-no"><a onclick="viewProfile(\'' . $value->ID . '\')">' . $name . '</a></h3>
                                <small class="text-muted">' . $title . '</small>
                                <br>
                                <h3>';
                                      if ($user_status == 0) {
                                          echo '<a data-toggle="tooltip"  data-original-title="Deny"  title="Deny"><i class="fa fa-minus-circle"></i></a>&nbsp;&nbsp;';
                                      } else if ($user_status == 1) {
                                          echo '<a data-toggle="tooltip"  data-original-title="Approved" title="Approved"><i class="fa fa-check-circle"></i></a>&nbsp;&nbsp;';
                                      } else if ($user_status == 2) {
                                          echo '<a data-toggle="tooltip"  data-original-title="Pending" title="Pending"><i class="fa exclamation-circle"></i></a>&nbsp;&nbsp;';
                                      }
                                      if (!empty($user_mobile)) {
                                          echo '<a href="tel:' . $user_mobile . '" data-toggle="tooltip"  data-original-title="' . $user_mobile . '" title="' . $user_mobile . '"><i class="fa fa-phone"></i></a>&nbsp;&nbsp;';
                                      }
                                      echo '<a data-toggle="tooltip"  data-original-title="Send Mail" onclick="sendMail(\'' . $value->ID . '\',\'' . $name . '\')" ><i class="fa fa-envelope"></i></a>&nbsp;&nbsp;                                    
                                    ';
                                      if (!empty($user_blog_url)) {
                                          echo '<a href="' . $user_blog_url . '" target="_blank" data-toggle="tooltip"  data-original-title="Blogs" title="Blogs"><i class="fa fa-th-large"></i></a>&nbsp;&nbsp;';
                                      }
                                      do_action('wp_user_hook_member_list_icon', $info);
                                      echo '<hr>
                                </h3>
                                <button type="button" class="btn ' . $wp_user_appearance_button_type . ' btn-default col-md-5" onclick="viewProfile(\'' . $value->ID . '\')">View Profile</button>
                                <span class="col-md-1"></span>';
                                      do_action('wp_user_hook_member_list_button', $info);
                                      echo '</div>
                        </div>
                    </div>';
                                  }
                                  echo '</div>';
                                  $count++;
                              }
                              echo '</div>';
                          }

                        $perpage = (isset($atts['size']) && $atts['size'] == 'small') ? 12 : 10;
                        ?>
                    </div>
                </div>
                <div class="clear"></div>
                <div class="row">
                    <div class="jplist-panel col-md-12">
                        <!-- items per page dropdown -->
                        <div
                            class="pagination dropdown jplist-items-per-page"
                            data-control-type="boot-items-per-page-dropdown"
                            data-control-name="paging"
                            data-control-action="paging">

                            <ul class="dropdown-menu"
                                role="menu"
                                aria-labelledby="dropdown-menu-1">

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="<?php echo $perpage ?>"
                                       data-default="true"><?php _e($perpage . ' per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#" data-number="20"
                                    ><?php _e('20 per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="50"><?php _e('50 per page', 'wpuser'); ?>
                                    </a>
                                </li>

                                <li role="presentation"
                                    class="divider"></li>

                                <li role="presentation">
                                    <a role="menuitem"
                                       tabindex="-1"
                                       href="#"
                                       data-number="all"><?php _e('ViewAll', 'wpuser'); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- pagination info label -->
                        <div
                            class="pagination jplist-pagination-info"
                            data-type="<strong>Page {current} of {pages}</strong><br/> <small>{start} - {end} of {all}</small>"
                            data-control-type="pagination-info"
                            data-control-name="paging"
                            data-control-action="paging">

                        </div>

                        <!-- bootstrap pagination control -->
                        <ul
                            class="pagination pull-right jplist-pagination"
                            data-control-type="boot-pagination"
                            data-control-name="paging"
                            data-control-action="paging"
                            data-range="3"
                            data-mode="google-like">
                        </ul>

                    </div>
                </div>
        </div>
        </form>
    </div>
    <?php
    echo '</div>';
    echo '</div>';
    echo '<div class="clear"></div>';
    return ob_get_clean();
}

    function wp_user_restrict($atts, $content = null)
    {

        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            if (isset($atts['role']) && !empty($atts['role']) && count(array_intersect($user->roles, explode(",", strtolower($atts['role'])))) >= 1) {
                return do_shortcode($content);
            }

            if (isset($atts['role']) && $atts['role'] == 'logged_in') {
                return do_shortcode($content);
            }

            return __("You do not have permission to access this content", 'wpuser');

        } else {
            $message = (isset($atts['message']) && !empty($atts['message'])) ? $atts['message'] : __('We’re sorry. You do not have permission to access this content. Please sign In to be granted access.', 'wpuser');
            return $message . " " . do_shortcode("[wp_user popup='1' width='700px']");
        }

    }
}

$GLOBALS['WPUserShortcode'] = new WPUserShortcode();