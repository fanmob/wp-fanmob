<?php

namespace Fanmob;

/**
 * Plugin Name: FanMob
 * Plugin URI: https://github.com/fanmob/wp-fanmob
 * Description: Adds support for embeddable FanMob poll widgets.
 * Version: 0.2.0
 * Author: FanMob <tom@fanmob.us>
 * Author URI: https://www.fanmob.us/
 * License: MIT
 */

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');

class UserPollsWidget extends \WP_Widget {
  function __construct() {
    parent::__construct(
      'fanmob_userpolls_widget',
      __('FanMob User Polls', 'text_domain'),
      array('description' =>
            __('Show polls created by a FanMob user.', 'text_domain'))
    );
  }

  /**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
  public function widget($args, $instance) {
    extract($args);

    $title = apply_filters('widget_title', $instance['title']);

    echo $before_widget;

    if ($title) {
      echo $before_title . $title . $after_title;
    }

    /* TODO: this isn't user-configurable... it should be eventually. */

    if (isset($instance['height'])) {
      $height = $instance['height'];
    } else {
      $height = '600px';
    }

    if (isset($instance['handle'])) {
      $handle = $instance['handle'];
      $url = "https://www.fanmob.us/embed/user_polls/$handle";

      /*
       * TODO: don't create the iframe directly, instead a div or
       * link or thing that sdk.js replaces with an iframe.
       */
?>
<iframe src="<? echo esc_attr($url); ?>"
        style="display: block; border: none; outline: none;
               min-width: 285px; height: <? echo esc_attr($height)?>;">
</iframe>
    <?php

    } else {
      echo '<p>Missing the handle for this widget (set in admin).</p>';
    }

    echo $after_widget;
  }

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}
		else {
			$title = __('Poll', 'text_domain');
		}

		if (isset($instance['handle'])) {
			$handle = $instance['handle'];
		}
		else {
			$handle = 'twitbeck3';
		}
?>
<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>">
    <?php _e( 'Title:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
         name="<?php echo $this->get_field_name( 'title' ); ?>"
         type="text" value="<?php echo esc_attr( $title ); ?>">

  <label for="<?php echo $this->get_field_id( 'handle' ); ?>">
    <?php _e( 'FanMob username:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'handle' ); ?>"
         name="<?php echo $this->get_field_name( 'handle' ); ?>"
         type="text" value="<?php echo esc_attr( $handle ); ?>">
</p>
  <?php
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
});
