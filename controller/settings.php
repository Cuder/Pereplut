<?php
$smarty->assign('settings',true);
$smarty->assign('sessionId',$sessionId);
if ($customBuilds) $smarty->assign('customBuilds',$customBuilds);
if (count($tocs) > 1) $smarty->assign('tocs',$tocs);
$smarty->assign('captions',$textStrings->captions->caption);
$smarty->assign('settings',$textStrings->settings->setting);
$smarty->assign('options',$textStrings->options->option);
$smarty->assign('button',$textStrings->buttons->button[1]);
if ($trailingCounter > 0) {
	$smarty->assign('trailingCounter',$trailingCounter);
	$settingTrailing = $textStrings->settings->setting[0];
	$settingTrailing = preg_replace('/\$trailingCounter/',$trailingCounter,$settingTrailing);
	if ($trailingCounter == 1) $settingTrailing = substr($settingTrailing, 0, -1);
	$smarty->assign('settingsTrailing',$settingTrailing);
}
$smarty->assign('maxBuildNameLength',$maxBuildNameLength);
$smarty->assign('tooltip',$textStrings->tooltips->tooltip[0]);