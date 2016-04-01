<?php

$module_id = "kontora.comments";
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);

if ($POST_RIGHT >= "R") :
	IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);

	$aTabs = array(
		array(
			"DIV"   => "edit1",
			"TAB"   => GetMessage("TAB1"),
			"TITLE" => GetMessage("TAB1_TITLE"),
		),
		array(
			"DIV"   => "edit2",
			"TAB"   => GetMessage("TAB2"),
			"TITLE" => GetMessage("TAB2_TITLE"),
		),
	);
	$tabControl = new CAdminTabControl("tabControl", $aTabs);

	if ($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && $POST_RIGHT == "W" && check_bitrix_sessid()) {
		COption::SetOptionString($module_id, 'premoderation', $_POST["premoderation"]);
		COption::SetOptionString($module_id, 'show_disagree', $_POST["show_disagree"]);
		COption::SetOptionString($module_id, 'wysiwyg', $_POST["wysiwyg"]);
		COption::SetOptionString($module_id, 'captcha', $_POST["captcha"]);
		COption::SetOptionString($module_id, 'send_to_email', $_POST["send_to_email"]);
		COption::SetOptionString($module_id, 'disagree', $_POST["disagree"]);

		$Update = $Update.$Apply;
		ob_start();
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
		ob_end_clean();

		if (strlen($_REQUEST["back_url_settings"]) > 0) {
			if ((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
			else
				LocalRedirect($_REQUEST["back_url_settings"]);
		} else {
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
		}
	}
	?>
	<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?
		$tabControl->Begin();
		$tabControl->BeginNextTab();
		?>
		<tr>
			<td width="50%"><?= GetMessage("PREMODERATION") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "premoderation") == 1) ? "checked" : "" ?> name="premoderation" value="1" />
			</td>
		</tr>
		<tr>
			<td><?= GetMessage("SHOW_DISAGREE") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "show_disagree") == 1) ? "checked" : "" ?> name="show_disagree" value="1" />
			</td>
		</tr>
		<tr>
			<td><?= GetMessage("WYSIWYG") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "wysiwyg") == 1) ? "checked" : "" ?> name="wysiwyg" value="1" />
			</td>
		</tr>
		<tr>
			<td><?= GetMessage("CAPTCHA") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "captcha") == 1) ? "checked" : "" ?> name="captcha" value="1" />
			</td>
		</tr>
		<tr>
			<td><?= GetMessage("send_to_email") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "send_to_email") == 1) ? "checked" : "" ?> name="send_to_email" value="1" />
			</td>
		</tr>
		<tr>
			<td><?= GetMessage("disagree") ?></td>
			<td>
				<input type="checkbox" <?= (COption::GetOptionString($module_id, "disagree") == 1) ? "checked" : "" ?> name="disagree" value="1" />
			</td>
		</tr>
		<?
		$tabControl->BeginNextTab();
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
		$tabControl->Buttons();
		?>
			<input <?if ($POST_RIGHT < "W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
			<input <?if ($POST_RIGHT < "W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
			<?
			if (strlen($_REQUEST["back_url_settings"]) > 0):?>
				<input <?if ($POST_RIGHT < "W") echo "disabled" ?> type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
				<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
			<?
			endif;
			echo bitrix_sessid_post();
		$tabControl->End();
		?>
	</form>
<?
endif;?>