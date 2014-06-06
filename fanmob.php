<?php

namespace Fanmob;

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');

require_once 'multi_polls_widget.php';

/**
 * Plugin Name: FanMob
 * Plugin URI: https://github.com/fanmob/wp-fanmob
 * Description: Adds support for embeddable FanMob poll widgets.
 * Version: 0.3.0
 * Author: FanMob <tom@fanmob.us>
 * Author URI: https://www.fanmob.us/
 * License: MIT
 */

class UserPollsWidget extends MultiPollsWidget {
  const DEFAULT_HANDLE = 'twitbeck3';

  function __construct() {
    \WP_Widget::__construct(
      'fanmob_userpolls_widget',
      __('FanMob User Polls', 'text_domain'),
      array('description' =>
            __('Show polls created by a FanMob user.', 'text_domain'))
    );
  }

  protected static function embed_url($handle) {
    return "https://www.fanmob.us/embed/user_polls/$handle";
  }
}

class GroupPollsWidget extends MultiPollsWidget {
  const DEFAULT_HANDLE = 'Cover32';

  function __construct() {
    \WP_Widget::__construct(
      'fanmob_grouppolls_widget',
      __('FanMob Group Polls', 'text_domain'),
      array('description' =>
            __('Show polls created by all users of a FanMob group.', 'text_domain'))
    );
  }

  protected static function embed_url($handle) {
    return "https://www.fanmob.us/embed/group_polls/$handle";
  }
}

function initialize_plugin() {
  /* would use wp_enqueue_scripts and just load the script
   * synchronously, but the JS SDK needs that #fnmb-jssdk ID on the
   * script tag. */

  add_action('wp_footer', NS . 'print_script_loader');
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

  $href = "https://www.fanmob.us/p/$id";

  return "<a href='" . esc_attr($href) . "' data-fnmb-embed='poll' " .
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
   }(document,'script','fnmb-jssdk','https://www.fanmob.us'));
  </script>
<?php

}

add_action('init', NS . 'initialize_plugin');
add_action('widgets_init', function () {
  register_widget(NS . 'UserPollsWidget');
  register_widget(NS . 'GroupPollsWidget');
});
