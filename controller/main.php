<?php
$smarty->assign('title',$textStrings->commonStrings->commonString[0]);
$smarty->display('main.tpl');
$smarty->clearAllCache();