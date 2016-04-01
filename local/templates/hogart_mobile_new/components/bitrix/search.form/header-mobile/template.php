<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<section class="slide hide" id="search">
	<div class="search-form">
		<form action="<?=$arResult["FORM_ACTION"]?>" class="main-form ">
			<div class="field">
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
				<label>Введите название или артикул</label>
				<input type="text" name="q" value="<?=$_REQUEST['q']?>" placeholder="Наименование или артикул"/>
				<?endif;?>
			</div>
			<input type="submit" value="найти">
		</form>
	</div>
</section>