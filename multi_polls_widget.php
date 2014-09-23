<?php

namespace Fanmob;

abstract class MultiPollsWidget extends \WP_Widget {
  const DEFAULT_HEIGHT = 350;
  const NEEDS_HANDLE = true; // FIXME: remove when removing user/group widgets

  abstract protected static function embed_url_base($handle);

  protected static function embed_url($handle, $topic) {
    $base = static::embed_url_base($handle);
    if (is_null($topic)) {
      return $base;
    } else {
      return $base . "?topic=" . urlencode($topic);
    }
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

    if (isset($instance['topic'])) {
      $topic = $instance['topic'];
    } else {
      $topic = null;
    }

    if (static::NEEDS_HANDLE && empty($instance['handle'])) {
      echo '<p>Missing the handle for this widget (set in admin).</p>';
    } else {
      $handle = $instance['handle'];
      $url = static::embed_url($handle, $topic);

      /*
       * TODO: don't create the iframe directly, instead a div or
       * link or thing that sdk.js replaces with an iframe.
       */
?>
<iframe src="<?php echo esc_attr($url); ?>"
        style="display: block; border: none; outline: none;
               min-width: 295px; height: <?php echo esc_attr($height)?>;">
</iframe>
<?php
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

    if (static::NEEDS_HANDLE && isset($instance['handle'])) {
      $handle = $instance['handle'];
    }
    else if (static::NEEDS_HANDLE) {
      $handle = static::DEFAULT_HANDLE;
    }

    if (isset($instance['height'])) {
      $height = $instance['height'];
    }
    else {
      $height = static::DEFAULT_HEIGHT;
    }

    if (isset($instance['topic'])) {
      $topic = $instance['topic'];
    } else {
      $topic = null;
    }

?>
<p>
  <label for="<?php echo $this->get_field_id( 'title' ); ?>">
    <?php _e( 'Title:' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
         name="<?php echo $this->get_field_name( 'title' ); ?>"
         type="text" value="<?php echo esc_attr( $title ); ?>">

<?php if (static::NEEDS_HANDLE): ?>
  <label for="<?php echo $this->get_field_id( 'handle' ); ?>">
    <?php _e( 'FanMob user or group name:' ); // FIXME ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'handle' ); ?>"
         name="<?php echo $this->get_field_name( 'handle' ); ?>"
         type="text" value="<?php echo esc_attr( $handle ); ?>">
<?php endif; ?>

  <label for="<?php echo $this->get_field_id( 'topic' ); ?>">
    <?php _e( 'Topic (optional):' ); ?>
  </label>
  <input class="widefat" id="<?php echo $this->get_field_id( 'topic' ); ?>"
         name="<?php echo $this->get_field_name( 'topic' ); ?>"
         type="text" value="<?php echo esc_attr( $topic ); ?>">

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
    if (static::NEEDS_HANDLE) {
      $instance['handle'] = (!empty($new_instance['handle'])) ? $new_instance['handle'] : null;
    }
    $instance['topic'] = (!empty($new_instance['topic'])) ? $new_instance['topic'] : null;
    $instance['height'] = (!empty($new_instance['height'])) ?
      intval($new_instance['height'])
      : static::DEFAULT_HEIGHT;

    return $instance;
  }
}
