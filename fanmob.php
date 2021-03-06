<?php

namespace Fanmob;

require_once 'constants.php';
require_once 'multi_polls_widget.php';
require_once 'domain_polls_widget.php';

/**
 * Plugin Name: FanMob
 * Plugin URI: https://github.com/fanmob/wp-fanmob
 * Description: Adds support for embeddable FanMob poll widgets.
 * Version: 0.6.0
 * Author: FanMob <tom@fanmob.us>
 * Author URI: https://www.fanmob.us/
 * License: MIT
 */

/**
 * @deprecated Use a domain polls widget
 */
class UserPollsWidget extends MultiPollsWidget {
  const DEFAULT_HANDLE = 'twitbeck3';

  function __construct() {
    $desc = __('Show polls created by a FanMob user. This widget will be removed; ' .
      'use a FanMob Site Polls widget instead.', 'text_domain');
    \WP_Widget::__construct('fanmob_userpolls_widget',
      __('FanMob User Polls (DEPRECATED)', 'text_domain'),
      array('description' => $desc));
  }

  protected static function embed_url_base($handle) {
    return FANMOB_BASE . "embed/user_polls/$handle";
  }
}

/**
 * @deprecated Use a domain polls widget
 */
class GroupPollsWidget extends MultiPollsWidget {
  const DEFAULT_HANDLE = 'Cover32';
  function __construct() {
    $desc = __('Show polls created by all users of a FanMob group. This widget will ' .
      'be removed; use a FanMob Site Polls widget instead.', 'text_domain');
    \WP_Widget::__construct('fanmob_grouppolls_widget',
      __('FanMob Group Polls (DEPRECATED)', 'text_domain'),
      array('description' => $desc));
  }

  protected static function embed_url_base($handle) {
    return FANMOB_BASE . "embed/group_polls/$handle";
  }
}

function initialize_plugin() {
  add_action('wp_footer', NS . 'print_script_loader');
  add_action('media_buttons', NS . 'media_buttons', 11);
  add_action('admin_enqueue_scripts', NS . 'load_admin_scripts');
  add_shortcode('fanmob', NS . 'poll_shortcode');
}

function poll_shortcode($atts) {
  $a = shortcode_atts(array(
    'id' => null
  ), $atts);

  $id = $a['id'];
  if (empty($id)) {
    return 'usage: [fanmob id="poll-id"]';
  }

  $href = FANMOB_BASE . "p/$id";

  return "<a href='" . esc_url($href) . "' data-fnmb-embed='poll' " .
         "data-fnmb-id='" . esc_attr($id) . "'>View poll on FanMob</a>";
}

function print_script_loader() {
?>
  <script type="text/javascript">
   (function(d, s, id, or) {
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) return;
     js = d.createElement(s); js.id = id;
     js.src = or + "/assets/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
    }(document,'script','fnmb-jssdk','<?= FANMOB_BASE ?>'));
  </script>
<?php

}

function media_buttons() {
  add_thickbox();
  $url = FANMOB_BASE . "new-poll/?context=wordpress";
  $url .= "&context_origin=" . urlencode(FANMOB_ORIGIN);
  $url .= "&TB_iframe=true";
  echo "<a href='" . esc_url($url) . "' class='button thickbox' title='FanMob Poll'>";
  echo "<span class='wp-media-buttons-icon fm-media-button-poll-icon'></span>";
  echo " Add FanMob Poll";
  echo "</a>";
}

function load_admin_scripts($hook) {
  wp_register_style(NS . 'wp_admin_css', plugins_url('css/admin.css', __FILE__));
  wp_enqueue_style(NS . 'wp_admin_css');
  wp_enqueue_script(NS . 'wp_admin_js',
      plugins_url('js/admin.js', __FILE__),
      array('jquery'));
  wp_localize_script(NS . 'wp_admin_js', 'WpFanmob', array(
      'origin' => FANMOB_ORIGIN
  ));
}

add_action('init', NS . 'initialize_plugin');
add_action('widgets_init', function () {
  register_widget(NS . 'UserPollsWidget');
  register_widget(NS . 'GroupPollsWidget');
  register_widget(NS . 'DomainPollsWidget');
});
