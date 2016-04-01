<?
foreach ($arResult['QUESTIONS'] as $code => &$arQuestion) {
    /** @var simple_html_dom_node $obHtmlNode  */
    /** @var simple_html_dom_node $obHtmlNodeLink  */
    $obHtmlNode = Sunra\PhpSimple\HtmlDomParser::str_get_html( BXHelper::utf8_to_entities($arQuestion['HTML_CODE']) )->firstChild();
    if (is_array($arParams['~CUSTOM_INPUT_PARAMS'][$code])) {
        foreach ($arParams['~CUSTOM_INPUT_PARAMS'][$code] as $attr => $val) {
            $obHtmlNode->setAttribute($attr,$val);
        }
    }
    $name = $obHtmlNode->getAttribute('name');
    $arQuestion["STRUCTURE"]["HTML_ID"] = $name;
    foreach ($arParams['MERGED_FIELDS'] as $arMergedPair) {
        if ($arMergedPair[0] == $code) {
            $arQuestion["TOP_WRAPPER"] = "<div class=\"field\" ".$arParams['~CUSTOM_WRAPPER_PARAMS'][$code]."><div class=\"small_col fl\">";
            $arQuestion["BOT_WRAPPER"] = "</div>";
            continue 2;
        } else if ($arMergedPair[1] == $code) {
            $arQuestion["TOP_WRAPPER"] = "<div class=\"small_col fr\">";
            $arQuestion["BOT_WRAPPER"] = "</div></div>";
            continue 2;
        }
    }
    $class = !empty($arParams["CUSTOM_WRAPPER_CSS"][$code]) ? $arParams["CUSTOM_WRAPPER_CSS"][$code] : "field custom_label";
    $arQuestion["TOP_WRAPPER"] = "<div class=\"".$class."\" ".$arParams['~CUSTOM_WRAPPER_PARAMS'][$code].">";
    $arQuestion["BOT_WRAPPER"] = "</div>";
    $obHtmlNode->setAttribute('id',$name);
    if ($arQuestion['REQUIRED'] == "Y") {
        $caption = !empty($arParams['CUSTOM_CAPTION'][$code]) ? $arParams['CUSTOM_CAPTION'][$code] : $arQuestion['CAPTION'];
        $message = !empty($arParams['CUSTOM_REQUIRED_MESS'][$code]) ? $arParams['CUSTOM_REQUIRED_MESS'][$code] : 'Пожалуйста заполните это поле';
        $obHtmlNode->setAttribute('data-rule-required','true');
        $obHtmlNode->setAttribute('data-msg-required',$message);
    }
    if (isset($arParams["CUSTOM_VALS"][$code])) {
        $value = $arParams["CUSTOM_VALS"][$code];
        $obHtmlNode->setAttribute('value',$value);
    }
    if ($code == $arParams["PHONE_CODE"]) {
        $obHtmlNode->setAttribute('class','masked');
    } else {
        $obHtmlNode->setAttribute('class','');
    }
    $arQuestion['HTML_CODE'] = $obHtmlNode->outertext();
//    unset($obHtmlNode);
}

$obHtmlNode = Sunra\PhpSimple\HtmlDomParser::str_get_html( $arResult['FORM_HEADER'] )->firstChild();
$header_class = "validate ajax-userform original";
if (!empty($arParams["~CUSTOM_SUCCESS_URL"])) {
    $header_class .= " result-redirect-timeout";
    $obHtmlNode->setAttribute("data-success-url",$arParams["~CUSTOM_SUCCESS_URL"]);
} else if ($arParams['SUCCESS_RELOAD'] == "N")  {
    $header_class .= "";
} else {
    $header_class .= " reload";
}
$obHtmlNode->setAttribute('class',$header_class);
$arResult['FORM_HEADER'] = $obHtmlNode->outertext();

$arResult['SUCCESS'] = intval($arResult["isFormNote"] == "Y" && $arResult["isFormErrors"] == "N" && $_REQUEST["formresult"] == "addok" && $_REQUEST['WEB_FORM_ID'] == $arParams['WEB_FORM_ID']) ? "Y":"N";
?>