<?php

namespace Fanmob;

define(__NAMESPACE__ . '\NS', __NAMESPACE__ . '\\');

if (getenv("FANMOB_BASE_URL")) {
  $base_url = getenv("FANMOB_BASE_URL");
} else {
  $base_url = "https://www.fanmob.us/";
}

if (getenv("FANMOB_EMBED_BASE_URL")) {
  $embed_base_url = getenv("FANMOB_EMBED_BASE_URL");
} else {
  $embed_base_url = "http://embed.fanmob.us/";
}

define(__NAMESPACE__ . '\FANMOB_BASE', $base_url);
define(__NAMESPACE__ . '\FANMOB_EMBED_BASE', $embed_base_url);
define(__NAMESPACE__ . '\FANMOB_ORIGIN', substr($base_url, 0, -1));
