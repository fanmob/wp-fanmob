wp-fanmob
=========

Wordpress plugin for [FanMob](https://www.fanmob.us/).

Installation
------------

Download the [latest release](https://github.com/fanmob/wp-fanmob/releases) as
a zip file and then follow the [instructions given at the Wordpress
Codex][instructions].

Usage
-----

To embed a poll, use the `fanmob` shortcode:

```
[fanmob id="6dde9f02-5085-4b82-bcf3-1887e1d22229"]
```

This will embed the poll at
<https://www.fanmob.us/p/6dde9f02-5085-4b82-bcf3-1887e1d22229/> in your post.

You can also create a poll directly from the post editor by clicking the `Add FanMob
Poll` button.  When you have finished creating the poll, its shortcode will be
inserted automatically in the post editor.

Requirements
------------

`wp-fanmob` requires PHP 5.3 or newer.

[instructions]: http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation
