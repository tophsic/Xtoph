#!/bin/sh

#############################################################################
# Xtoph Framework
#
# LICENSE
#
# This source file is subject to the new BSD license that is bundled
# with this package in the file LICENSE.txt.
# It is also available through the world-wide-web at this URL:
# http://framework.zend.com/license/new-bsd
# If you did not receive a copy of the license and are unable to
# obtain it through the world-wide-web, please send an email
# to license@zend.com so we can send you a copy immediately.
#
# Xtoph
# Christophe Sicard (http://christophe.plom.net)
# http://christophe.plom.net/license/new-bsd     New BSD License
#############################################################################


# find php: pear first, command -v second, straight up php lastly
if test "@php_bin@" != '@'php_bin'@'; then
    PHP_BIN="@php_bin@"
elif command -v php 1>/dev/null 2>/dev/null; then
    PHP_BIN=`command -v php`
else
    PHP_BIN=php
fi

# find xtoph.php: same directory, 
SELF_LINK="$0"
SELF_LINK_TMP="$(readlink "$SELF_LINK")"
while test -n "$SELF_LINK_TMP"; do
    SELF_LINK="$SELF_LINK_TMP"
    SELF_LINK_TMP="$(readlink "$SELF_LINK")"
done
PHP_DIR="$(dirname "$SELF_LINK")"

"$PHP_BIN" -d safe_mode=Off -f "$PHP_DIR/xtoph.php" -- "$@"

