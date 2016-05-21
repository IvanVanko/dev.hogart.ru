<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<div class="search-cnt">
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<?if($arParams["USE_SUGGEST"] === "Y"):
			$APPLICATION->IncludeComponent(
				"bitrix:search.suggest.input",
				"",
				array(
					"NAME" => "q",
					"VALUE" => "",
					"INPUT_SIZE" => 15,
					"DROPDOWN_SIZE" => 10,
				),
				$component, array("HIDE_ICONS" => "Y")
			);
		else:?>
			<input type="text" name="q" value="<?=$_REQUEST['q']?>" placeholder="<?=GetMessage('BSF_T_PLACEHOLDER')?>"/>
		<?endif;?>
		
		<button class="icon-search icon-full"></button>
	</form>
</div>