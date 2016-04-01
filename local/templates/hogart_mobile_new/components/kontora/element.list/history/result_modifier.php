<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (IsMadeonMobile() && eregi("history", $APPLICATION->GetCurDir() ) ) 
{
	#$APPLICATION->AddChainItem("О компании", "/company/");
}

