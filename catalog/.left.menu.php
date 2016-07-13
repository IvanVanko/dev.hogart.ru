<?
global $APPLICATION;
$aMenuLinks = [];

if ($APPLICATION->GetCurDir() == SITE_DIR) {
    $aMenuLinks[] = Array(
        "Бренды",
        "/brands/",
        Array(),
        Array(),
        ""
    );

    $aMenuLinks[] = Array(
        "Документация",
        "/documentation/",
        Array(),
        Array(),
        ""
    );
}

?>