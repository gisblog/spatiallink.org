<?php
// Database settings
define("C_DB_TYPE", 'mysql');
define("C_DB_HOST", 'spatiallink.db.3881957.hostedresource.com');
define("C_DB_NAME", 'spatiallink');
define("C_DB_USER", 'spatiallink');
define("C_DB_PASS", 'XSQLhvp246#');
define("C_MSG_TBL", 'chat_messages');
define("C_USR_TBL", 'chat_log_users');
define("C_REG_TBL", 'chat_reg_users');
define("C_BAN_TBL", 'chat_ban_users');

// Cleaning settings for messages and usernames
define("C_MSG_DEL", '168');
define("C_USR_DEL", '60');
define("C_REG_DEL", '60');

// Proposed rooms
$DefaultChatRooms = array('Chat');
$DefaultPrivateRooms = array();

// Language settings
define("C_LANGUAGE", 'english');
define("C_MULTI_LANG", '1');

// Registration of users
define("C_REQUIRE_REGISTER", '0');
define("C_EMAIL_PASWD", '0');

// Security and restriction settings
define("C_SHOW_ADMIN", '1');
define("C_SHOW_DEL_PROF", '1');
define("C_VERSION", '0');
define("C_BANISH", '0');
define("C_NO_SWEAR", '0');
define("C_SAVE", '0');

// Messages enhancements
define("C_USE_SMILIES", '1');
define("C_HTML_TAGS_KEEP", 'simple');
define("C_HTML_TAGS_SHOW", '1');

// Default display seetings
define("C_TMZ_OFFSET", '0');
define("C_MSG_ORDER", '1');
define("C_MSG_NB", '20');
define("C_MSG_REFRESH", '10');
define("C_SHOW_TIMESTAMP", '0');
define("C_NOTIFY", '1');
define("C_WELCOME", '1');
?>