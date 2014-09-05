<?php

namespace Fanmob;

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');

if (getenv("FANMOB_BASE_URL")) {
  $base_url = getenv("FANMOB_BASE_URL");
} else {
  $base_url = "https://www.fanmob.us/";
}

define(__NAMESPACE__ . '\FANMOB_BASE', $base_url);

