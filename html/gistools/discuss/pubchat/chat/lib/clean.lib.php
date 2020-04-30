<?php
$Chat = new DB;
$Chat->query("DELETE FROM ".C_MSG_TBL." WHERE m_time < ".(time() - C_MSG_DEL * 60 * 60));
$Chat->query("DELETE FROM ".C_USR_TBL." WHERE u_time < ".(time() - C_USR_DEL * 60)." OR (status = 'k' AND u_time <  ".(time() - 20).")");
$CleanUsrTbl = ($Chat->affected_rows() > 0);
$Chat->close();
?>