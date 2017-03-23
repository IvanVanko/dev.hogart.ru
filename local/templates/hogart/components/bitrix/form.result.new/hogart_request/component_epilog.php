<?
$result = $arResult['SUCCESS'] == "Y";
CStorage::setVar($result,"request_success_".$_REQUEST['WEB_FORM_ID']);
if ($result) echo "<input type=\"hidden\" name=\"success\" value=\"Y\">";
CStorage::setVar($arResult['arForm']['SID'],"seminar_form_name");
$fields = BXHelper::getFormFields($id, array('SORT' => 'ASC'), array('SID' => 'SEMINAR_EAN_CODE'));

//if ($result && !empty($arParams['~CUSTOM_SUCCESS_URL'])) echo $arResult['QUESTIONS']['SEMINAR_REGISTRATION_NUMBER']['HTML_CODE'];
if ($result && !empty($arParams['~CUSTOM_SUCCESS_URL'])) {
    echo "<input type=\"hidden\" name=\"result_id\" value=\"".$_REQUEST['RESULT_ID']."\">";
}
?>