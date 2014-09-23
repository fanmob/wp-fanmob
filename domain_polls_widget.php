<?php

namespace Fanmob;

require_once 'constants.php';
require_once 'multi_polls_widget.php';

class DomainPollsWidget extends MultiPollsWidget {
  const NEEDS_HANDLE = false;

  function __construct() {
    $desc = __('Show all FanMob polls embedded on this site', 'text_domain');
    \WP_Widget::__construct('fanmob_sitepolls_widget',
      __('FanMob Site Polls', 'text_domain'),
      array('description' => $desc));
  }

  static function embed_url_base($handle) {
    $domain = $_SERVER['SERVER_NAME'];
    return FANMOB_EMBED_BASE . "domain_polls/$domain/";
  }
}
