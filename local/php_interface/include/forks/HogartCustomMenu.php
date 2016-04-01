<?
class HogartCustomMenu extends CMenuCustom
{
    function AddItem($type="left", $arItem=array())
    {
        if (count($arItem) <= 0)
            return;

        if (!array_key_exists("TEXT", $arItem) || strlen(trim($arItem["TEXT"])) <= 0)
            return;

        if (!array_key_exists("LINK", $arItem) || strlen(trim($arItem["LINK"])) <= 0)
            $arItem["LINK"] = "";

        if (!array_key_exists("SELECTED", $arItem))
            $arItem["SELECTED"] = false;

        if (!array_key_exists("PERMISSION", $arItem))
            $arItem["PERMISSION"] = "R";

        if (!array_key_exists("DEPTH_LEVEL", $arItem))
            $arItem["DEPTH_LEVEL"] = 1;

        if (!array_key_exists("IS_PARENT", $arItem))
            $arItem["IS_PARENT"] = false;

        if (!array_key_exists("PARAMS", $arItem))
            $arItem["PARAMS"] = false;

        $this->arItems[$type][] = array(
            "TEXT" => $arItem["TEXT"],
            "LINK" => $arItem["LINK"],
            "SELECTED" => $arItem["SELECTED"],
            "PERMISSION" => $arItem["PERMISSION"],
            "DEPTH_LEVEL" => $arItem["DEPTH_LEVEL"],
            "IS_PARENT" => $arItem["IS_PARENT"],
            "PARAMS" => $arItem["PARAMS"]
        );
    }
}
?>