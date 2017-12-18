<?php
echo '<div class="wrapper">';

global $current_user, $wp_roles;

$wp_user_disable_user_sidebar = get_option('wp_user_disable_user_sidebar');

$args = array(
    'role' => '',
    'role__in' => array(),
    'role__not_in' => array(),
    'meta_key' => '',
    'meta_value' => '',
    'meta_compare' => '',
    'meta_query' => array(),
    'date_query' => array(),
    'include' => array(),
    'exclude' => array(),
    'offset' => '',
    'search' => '',
    'number' => '',
    'count_total' => false,
    'fields' => 'all',
);


$user_id = get_current_user_id();
$attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
$title = (get_user_meta($user_id, 'user_title', true));
$user_status = (get_user_meta($user_id, 'wp-approve-user', true));
// retrieve the thumbnail size of our image
$attachment_url = esc_url(get_the_author_meta('user_meta_image', $user_id));
$attachment_id = profileController::get_attachment_image_by_url($attachment_url);
// retrieve the thumbnail size of our image

$image_thumb = wp_get_attachment_image_src($attachment_id, 'thumbnail');
// return the image thumbnail
if (!empty($image_thumb[0])) {
    $wp_user_profile_img = $image_thumb[0];
} else if (!empty($attachment_url)) {
    $wp_user_profile_img = $attachment_url;
} else {
    $args = get_avatar_data($user_id);
    if (!empty($args['url']))
        $wp_user_profile_img = $args['url'];
    else
        $wp_user_profile_img = WPUSER_PLUGIN_URL . 'assets/images/wpuser.png';
}


$name = get_the_author_meta('first_name', $user_id) . " " . get_the_author_meta('last_name', $user_id);
$user_mobile = get_the_author_meta('user_mobile', $user_id);
$authors_posts = get_posts(array('author' => $user_id, 'post_status' => 'publish'));
$user_blog_url = (count($authors_posts)) ? get_author_posts_url($user_id) : '';
if (empty(str_replace(' ', '', $name))) {
    $user_info = get_userdata($user_id);
    $name = $user_info->display_name;
    if (empty($name)) {
        $name = $user_info->user_nicename;
    }
    if (empty($name)) {
        $name = $user_info->user_login;
    }
}


$authors_posts = get_posts(array('author' => $user_id, 'post_status' => 'publish'));
// Get all user meta data for $user_id
$meta = get_user_meta($user_id);
$header_block_info = array(
    array(
        "name" => 'Blogs',
        "url" => get_author_posts_url($user_id),
        'icon' => 'fa fa-th-large',
        "count" => count($authors_posts),
    )
);

$current_user = wp_get_current_user();

$email = profileController::wpuser_profile_details('user_email', $user_id);
$user_url = profileController::wpuser_profile_details('user_url', $user_id);
$user_info_box = array(
    "First name" => $meta['first_name'][0],
    "Last name" => $meta['last_name'][0],
    "Email" => $email,//(isset($meta['user_email']) && !empty($meta['user_email'])) ? $meta['user_email'] : '',
    'User URL' => $user_url,
);

$header_block_info = apply_filters('wp_user_member_filter_header_block', $header_block_info, $user_id);
$user_info_box = apply_filters('wp_user_member_info', $user_info_box, $user_id);

if (get_option('wp_user_disable_user_bar') != 1) {
    do_action('wpuser_profile_header', $atts);
}
?>

<div class="row">

    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary wpuser-custom-box">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle wpuser_profile_img"
                     src="<?php echo $wp_user_profile_img ?>"
                     alt="User profile picture">

                <h3 class="profile-username text-center wpuser_profile_name"><?php echo $name ?></h3>

                <p class="text-muted text-center"><?php echo $title ?></p>

                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        <?php foreach ($header_block_info as $header_block) {
                            $link_attr=(($header_block['url'])=='#') ? 'onclick="getFollowerList(\'' . $user_id . '\',\'\',\''. $header_block['type'] .'\',\'1\')"' : ' ';
                            $link_attr .=(($header_block['url'])=='#') ? ' ' : " href='" . $header_block['url'] ."' target='_blank' ";
                            echo '<li><a '.$link_attr.'><i class="' . $header_block['icon'] . '"></i>&nbsp;&nbsp;' . $header_block['name'] . ' <span class="pull-right badge bg-' . $wp_user_appearance_skin_color . '">' . $header_block['count'] . '</span></a></li>';

                        } ?>
                    </ul>
                </div>

                <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                <?php do_action('wp_user_hook_member_list_button_my_profile'); ?>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary wpuser-custom-box">
            <div class="box-header with-border">
                <h3 class="box-title"><?php _e('Profile Information', 'wpuser') ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- form start -->
                <form class="form-horizontal">
                    <div class="box-body">
                        <?php foreach ($user_info_box as $key => $value) {
                            ?>
                            <div class="form-group col-md-12">
                                <label for="<?php echo $key ?>" class=" control-label"><?php echo $key ?> :</label>
                                <label id="<?php echo $key ?>"
                                       class="wpuser_profile_<?php echo strtolower(str_replace(' ', '_', $key)) ?>"
                                       style="color:Gray !important"><?php echo $value ?></label>

                            </div>

                        <?php } ?>
                    </div>

                </form>
            </div>
            <div class="box-footer">
                <a href="<?php echo wp_logout_url(get_permalink()) ?>"
                   class="btn btn-default btn-block"><?php _e('Logout', 'wpuser') ?></a>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-<?php echo ($wp_user_disable_user_sidebar != 1) ? '6' : '9'; ?>">
        <div class="nav-tabs-custom wpuser-custom-nav" id="myProfileSection">
            <ul class="nav nav-tabs">
                <?php foreach ($wp_user_profile as $tab => $user_profile) {
                    echo ' <li class="' . $user_profile['active'] . '"><a class="" href="#' . $tab . '" data-toggle="tab">' . $user_profile['tab'] . '</a></li>';
                } ?>
            </ul>
            <div class="tab-content">
                <?php
                foreach ($wp_user_profile as $tab => $user_profile) {
                    echo '<div class="tab-pane ' . $user_profile['active'] . '" id="' . $tab . '">                                    ';
                    $WPclass = $user_profile['class'];
                    $WPfunction = $user_profile['function'];
                    $WPclass::$WPfunction($atts);
                    echo '</div>';
                } ?>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        
        <div id="myNotification" style="display:none">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-bell-o"></i>
                    <h3 class="box-title"><?php _e('Notifications','wpuser')?></h3>
                    <button type="button" onclick="closeNotification()" class="btn btn-default btn-flat pull-right ">Close</button>
                    (<a href="#" onclick="removeNotification(0)"><?php _e('Clear All','wpuser') ?></a>)
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="myNotificationBody">
                    </div>

                </div>


        </div>
        <!-- /.nav-tabs-custom -->
    </div>

    <!-- /.col -->
    <?php
    if ($wp_user_disable_user_sidebar != 1) {
        echo ' <div class="col-md-3">';
        do_action('wpuser_action_profile_sidebar',$atts);
        echo '</div>';
    }
    ?>

    <!-- /.col -->
</div>
</div>
<style>
    .rowhover {
        color: white;
        float: right;
        margin-top: -145px;
        margin-right: 20px;
    }

    .rowhover:hover {
        color: Black;
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
</style>