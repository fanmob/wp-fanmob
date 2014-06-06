<?php

namespace Fanmob;

abstract class MultiPollsWidget extends \WP_Widget {
  const DEFAULT_HEIGHT = 500;

  protected static function embed_url($handle) {
    return "FIXME";
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

    if (isset($instance['height'])) {
      $height = $instance['height'] . 'px';
    } else {
      $height = static::DEFAULT_HEIGHT . 'px';
    }

    if (isset($instance['handle'])) {
      $handle = $instance['handle'];
      $url = static::embed_url($handle);

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
			$handle = static::DEFAULT_HANDLE;
		}

    if (isset($instance['height'])) {
			$height = $instance['height'];
		}
		else {
			$height = static::DEFAULT_HEIGHT;
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
    <?php _e( 'FanMob user or group name:' ); // FIXME ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'handle' ); ?>"
         name="<?php echo $this->get_field_name( 'handle' ); ?>"
         type="text" value="<?php echo esc_attr( $handle ); ?>">

  <label for="<?php echo $this->get_field_id( 'height' ); ?>">
    <?php _e( 'Height (in px)' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>"
         name="<?php echo $this->get_field_name( 'height' ); ?>"
         type="text" value="<?php echo esc_attr( $height ); ?>">
</p>
  <?php
  }
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? $new_instance['title'] : '';
    $instance['handle'] = (!empty($new_instance['handle'])) ? $new_instance['handle'] : null;
    $instance['height'] = (!empty($new_instance['height'])) ?
                          intval($new_instance['height'])
                                : static::DEFAULT_HEIGHT;

		return $instance;
	}
}
