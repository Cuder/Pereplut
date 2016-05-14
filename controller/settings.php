<?php
$smarty->assign('settings',true);
$smarty->assign('sessionId',$sessionId);
if ($trailingCounter > 0) $smarty->assign('trailingCounter',$trailingCounter);
if ($customBuilds) $smarty->assign('customBuilds',$customBuilds);
if (count($tocs) > 1) $smarty->assign('tocs',$tocs);