<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="main-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form name="<?=$arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="main-filter-form hidden_block open-block">

		<div class="filter-block">
			<a href="<?=$APPLICATION->GetCurDir();?>" class="input-btn gray-btn">сбросить</a>
			<!--<label for="select1">Сортировка</label>
			<select name="select1" id="select1">
				<option value="1">По артикулю</option>
				<option value="2">По артикулю</option>
			</select>/-->
		</div>

		<?foreach ($arResult["HIDDEN"] as $arItem):?>
			<input type="hidden" name="<?=$arItem["CONTROL_NAME"]?>" id="<?=$arItem["CONTROL_ID"]?>" value="<?=$arItem["HTML_VALUE"]?>" />
		<?endforeach;?>
		<?$expanded_fields_html = "";?>
		<?$collapsed_fields_html = "";?>
	<?
	ob_start();
	foreach ($arResult["ITEMS"] as $key => $arItem) {
		$key = $arItem["ENCODED_ID"];
		if (isset($arItem["PRICE"])):
			$start_min_value = is_numeric($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) : intval($arItem["VALUES"]["MIN"]["VALUE"]);
			$start_max_value = is_numeric($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) : intval($arItem["VALUES"]["MAX"]["VALUE"]);

			$min_value = intval($arItem["VALUES"]["MIN"]["VALUE"]);
			$max_value = intval($arItem["VALUES"]["MAX"]["VALUE"]);

			$input_min_value = is_numeric($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? intval($arItem["VALUES"]["MIN"]["HTML_VALUE"]):"";
			$input_max_value = is_numeric($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? intval($arItem["VALUES"]["MAX"]["HTML_VALUE"]):"";

			if (!$arItem["VALUES"]["MIN"]["VALUE"] || !$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])
				continue;
			?>
		<div class="filter-block">
			<p class="block-title">Стоимость, руб</p>
			<div class="values_wrap clearfix">
				<input type="text" class="min" value="<?=$start_min_value?>" readonly>
				<input type="text" class="max" value="<?=$start_max_value?>" readonly>	
			</div>
			<div class="slider" 
				data-format="money"
				data-min="<?=$min_value?>" 
				data-max="<?=$max_value?>" 
				data-start-value="<?=$start_min_value?>" 
				data-end-value="<?=$start_max_value?>" 
				data-step="1000">
			</div>
			<input class="slider-min" type="hidden" name="<?=$arItem["VALUES"]["MIN"]['CONTROL_NAME']?>" value="<?=$input_min_value?>" />
			<input class="slider-max" type="hidden" name="<?=$arItem["VALUES"]["MAX"]['CONTROL_NAME']?>" value="<?=$input_max_value?>" />		
			
		</div>

		<?endif;
	}
	$expanded_fields_html .= ob_get_clean();

	//not prices
	//print_r($arResult["ITEMS"]);
	//ob_start();
	foreach ($arResult["ITEMS"] as $key => $arItem) {
		echo "<span style=\"display: none\">".$arItem['CUSTOM_SECTION_SORT']." ".$arItem['NAME']."</span>";
		if (empty($arItem["VALUES"]) || isset($arItem["PRICE"]))
			continue;

		if (
			$arItem["DISPLAY_TYPE"] == "A"
			&& (
				!$arItem["VALUES"]["MIN"]["VALUE"]
				|| !$arItem["VALUES"]["MAX"]["VALUE"]
				|| $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"]
			)
		)
			continue;
		?>

				<?
				ob_start();
				/*if ($arItem['CODE'] != 'is_new'):?><h2><?=$arItem["NAME"]?></h2><?endif;*/?>
				<?
				$arCur = current($arItem["VALUES"]);
				switch ($arItem["DISPLAY_TYPE"])
				{
					case "A"://NUMBERS_WITH_SLIDER
					case "RV"://RANGE NUMBERS_WITH_SLIDER
					?><?
						$start_min_value = is_numeric($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]) : floatval($arItem["VALUES"]["MIN"]["VALUE"]);
						$start_max_value = is_numeric($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]) : floatval($arItem["VALUES"]["MAX"]["VALUE"]);

						$min_value = floatval($arItem["VALUES"]["MIN"]["VALUE"]);
						$max_value = floatval($arItem["VALUES"]["MAX"]["VALUE"]);

						$input_min_value = is_numeric($arItem["VALUES"]["MIN"]["HTML_VALUE"]) ? floatval($arItem["VALUES"]["MIN"]["HTML_VALUE"]):"";
						$input_max_value = is_numeric($arItem["VALUES"]["MAX"]["HTML_VALUE"]) ? floatval($arItem["VALUES"]["MAX"]["HTML_VALUE"]):"";

						//if (!is_numeric($arItem["VALUES"]["MIN"]["VALUE"]) || !is_numeric($arItem["VALUES"]["MAX"]["VALUE"]) || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])
							//continue;
						?>
	<!--<div class="filter-block">
	<p class="block-title">Стоимость, руб</p>
	<div class="values_wrap clearfix">
	<input type="text" class="min" value="<?=$min_value?>" readonly>
	<input type="text" class="max" value="<?=$max_value?>" readonly>	
	</div>
	<div class="slider" data-min="<?=$input_min_value?>" data-max="<?=$input_max_value?>" data-start-value="<?=$input_min_value?>" data-end-value="<?=$input_max_value?>" data-step="100"></div>
	<input class="slider-min" type="hidden" name="<?=$arItem["VALUES"]["MIN"]['CONTROL_NAME']?>" value="<?=$input_min_value?>" />
	<input class="slider-max" type="hidden" name="<?=$arItem["VALUES"]["MAX"]['CONTROL_NAME']?>" value="<?=$input_max_value?>" />			
	</div>/-->
						<?
						break;
					case "B"://NUMBERS
						?>
						<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container">
							<input
								class="min-price"
								type="text"
								name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
								id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
								value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								/>
						</div></div>
						<div class="bx_filter_parameters_box_container_block"><div class="bx_filter_input_container">
							<input
								class="max-price"
								type="text"
								name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
								id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
								value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								/>
						</div></div>
						<?
						break;
					case "G"://CHECKBOXES_WITH_PICTURES
						?>
						<?foreach ($arItem["VALUES"] as $val => $ar):?>
							<input
								style="display: none"
								type="checkbox"
								name="<?=$ar["CONTROL_NAME"]?>"
								id="<?=$ar["CONTROL_ID"]?>"
								value="<?=$ar["HTML_VALUE"]?>"
								<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<?
							$class = "";
							if ($ar["CHECKED"])
								$class.= " active";
							if ($ar["DISABLED"])
								$class.= " disabled";
							?>
							<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label dib<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'active');">
								<span class="bx_filter_param_btn bx_color_sl">
									<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
									<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
									<?endif?>
								</span>
							</label>
						<?endforeach?>
						<?
						break;
					case "H"://CHECKBOXES_WITH_PICTURES_AND_LABELS
						?>
						<?foreach ($arItem["VALUES"] as $val => $ar):?>
							<input
								style="display: none"
								type="checkbox"
								name="<?=$ar["CONTROL_NAME"]?>"
								id="<?=$ar["CONTROL_ID"]?>"
								value="<?=$ar["HTML_VALUE"]?>"
								<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
							/>
							<?
							$class = "";
							if ($ar["CHECKED"])
								$class.= " active";
							if ($ar["DISABLED"])
								$class.= " disabled";
							?>
							<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" onclick="smartFilter.keyup(BX('<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')); BX.toggleClass(this, 'active');">
								<span class="bx_filter_param_btn bx_color_sl">
									<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
										<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
									<?endif?>
								</span>
								<span class="bx_filter_param_text">
									<?=$ar["VALUE"]?>
								</span>
							</label>
						<?endforeach?>
						<?
						break;
					case "P"://DROPDOWN
						$checkedItemExist = false;
						?>
						<div class="bx_filter_select_container">
							<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
								<div class="bx_filter_select_text" data-role="currentOption">
									<?
									foreach ($arItem["VALUES"] as $val => $ar)
									{
										if ($ar["CHECKED"])
										{
											echo $ar["VALUE"];
											$checkedItemExist = true;
										}
									}
									if (!$checkedItemExist)
									{
										echo GetMessage("CT_BCSF_FILTER_ALL");
									}
									?>
								</div>
								<div class="bx_filter_select_arrow"></div>
								<input
									style="display: none"
									type="radio"
									name="<?=$arCur["CONTROL_NAME_ALT"]?>"
									id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
									value=""
								/>
								<?foreach ($arItem["VALUES"] as $val => $ar):?>
									<input
										style="display: none"
										type="radio"
										name="<?=$ar["CONTROL_NAME_ALT"]?>"
										id="<?=$ar["CONTROL_ID"]?>"
										value="<? echo $ar["HTML_VALUE_ALT"] ?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
									/>
								<?endforeach?>
								<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none;">
									<ul>
										<li>
											<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx_filter_param_label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
												<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
											</label>
										</li>
									<?
									foreach ($arItem["VALUES"] as $val => $ar):
										$class = "";
										if ($ar["CHECKED"])
											$class.= " selected";
										if ($ar["DISABLED"])
											$class.= " disabled";
									?>
										<li>
											<label for="<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" data-role="label_<?=$ar["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')"><?=$ar["VALUE"]?></label>
										</li>
									<?endforeach?>
									</ul>
								</div>
							</div>
						</div>
						<?
						break;
					case "R"://DROPDOWN_WITH_PICTURES_AND_LABELS
						?>
						<div class="bx_filter_select_container">
							<div class="bx_filter_select_block" onclick="smartFilter.showDropDownPopup(this, '<?=CUtil::JSEscape($key)?>')">
								<div class="bx_filter_select_text" data-role="currentOption">
									<?
									$checkedItemExist = false;
									foreach ($arItem["VALUES"] as $val => $ar):
										if ($ar["CHECKED"])
										{
										?>
											<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
												<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
											<?endif?>
											<span class="bx_filter_param_text">
												<?=$ar["VALUE"]?>
											</span>
										<?
											$checkedItemExist = true;
										}
									endforeach;
									if (!$checkedItemExist)
									{
										?><span class="bx_filter_btn_color_icon all"></span> <?
										echo GetMessage("CT_BCSF_FILTER_ALL");
									}
									?>
								</div>
								<div class="bx_filter_select_arrow"></div>
								<input
									style="display: none"
									type="radio"
									name="<?=$arCur["CONTROL_NAME_ALT"]?>"
									id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
									value=""
								/>
								<?foreach ($arItem["VALUES"] as $val => $ar):?>
									<input
										style="display: none"
										type="radio"
										name="<?=$ar["CONTROL_NAME_ALT"]?>"
										id="<?=$ar["CONTROL_ID"]?>"
										value="<?=$ar["HTML_VALUE_ALT"]?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
									/>
								<?endforeach?>
								<div class="bx_filter_select_popup" data-role="dropdownContent" style="display: none">
									<ul>
										<li style="border-bottom: 1px solid #e5e5e5;padding-bottom: 5px;margin-bottom: 5px;">
											<label for="<?="all_".$arCur["CONTROL_ID"]?>" class="bx_filter_param_label" data-role="label_<?="all_".$arCur["CONTROL_ID"]?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape("all_".$arCur["CONTROL_ID"])?>')">
												<span class="bx_filter_btn_color_icon all"></span>
												<? echo GetMessage("CT_BCSF_FILTER_ALL"); ?>
											</label>
										</li>
									<?
									foreach ($arItem["VALUES"] as $val => $ar):
										$class = "";
										if ($ar["CHECKED"])
											$class.= " selected";
										if ($ar["DISABLED"])
											$class.= " disabled";
									?>
										<li>
											<label for="<?=$ar["CONTROL_ID"]?>" data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label<?=$class?>" onclick="smartFilter.selectDropDownItem(this, '<?=CUtil::JSEscape($ar["CONTROL_ID"])?>')">
												<?if (isset($ar["FILE"]) && !empty($ar["FILE"]["SRC"])):?>
													<span class="bx_filter_btn_color_icon" style="background-image:url('<?=$ar["FILE"]["SRC"]?>');"></span>
												<?endif?>
												<span class="bx_filter_param_text">
													<?=$ar["VALUE"]?>
												</span>
											</label>
										</li>
									<?endforeach?>
									</ul>
								</div>
							</div>
						</div>
						<?
						break;
					case "K"://RADIO_BUTTONS
						?>
						<label class="bx_filter_param_label" for="<? echo "all_".$arCur["CONTROL_ID"] ?>">
							<span class="bx_filter_input_checkbox">
								<input
									type="radio"
									value=""
									name="<? echo $arCur["CONTROL_NAME_ALT"] ?>"
									id="<? echo "all_".$arCur["CONTROL_ID"] ?>"
									onclick="smartFilter.click(this)"
								/>
								<span class="bx_filter_param_text"><? echo GetMessage("CT_BCSF_FILTER_ALL"); ?></span>
							</span>
						</label>
						<?foreach($arItem["VALUES"] as $val => $ar):?>
							<label data-role="label_<?=$ar["CONTROL_ID"]?>" class="bx_filter_param_label" for="<? echo $ar["CONTROL_ID"] ?>">
								<span class="bx_filter_input_checkbox <? echo $ar["DISABLED"] ? 'disabled': '' ?>">
									<input
										type="radio"
										value="<? echo $ar["HTML_VALUE_ALT"] ?>"
										name="<? echo $ar["CONTROL_NAME_ALT"] ?>"
										id="<? echo $ar["CONTROL_ID"] ?>"
										<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
										onclick="smartFilter.click(this)"
									/>
									<span class="bx_filter_param_text"><? echo $ar["VALUE"]; ?></span>
								</span>
							</label>
						<?endforeach;?>
						<?
						break;
					default://CHECKBOXES
						?>
						<?if ($arItem['CODE'] == 'is_new'):?>
						<div class="filter-block">
							<p class="block-title"><?=$arItem["NAME"]?></p>
							<div class="checkbox_wrap">						
							<?foreach ($arItem["VALUES"] as $val => $ar):?>
							<input 
								type="checkbox" 
								class="custom_checkbox"
								value="<?=$ar["HTML_VALUE"]?>"
								name="<?=$ar["CONTROL_NAME"]?>"
								id="<?=$ar["CONTROL_ID"]?>"
								<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
								onclick="smartFilter.click(this)"
							/>
							<label for="<?=$ar["CONTROL_ID"]?>"><?=$arItem['NAME']?></label>					
							<?endforeach;?>
							</div>
						</div>							
						<?else:?>
							<?#DebugMessage($arItem["VALUES"],000);?>
							<div class="breands hide-big-cnt" data-hide="<?=$arItem["HINT"]?>">
								<?$hiddenValues = array();?>
								<?if (count($arItem["VALUES"]) > 5) {
									$hiddenValues = array_splice($arItem["VALUES"], 5, count($arItem["VALUES"]) - 5);
								}?>
								<?if (!empty($arItem["VALUES"])) {?>
										<?#DebugMessage($arItem["VALUES"]);?>
										<div class="filter-block">
										<p class="block-title"><?=$arItem["NAME"]?></p>
											<div class="checkbox_wrap open-block">
											<?foreach ($arItem["VALUES"] as $val => $ar):?>
													
													<input
														class="custom_checkbox"
														type="checkbox"
														value="<?=$ar["HTML_VALUE"]?>"
														name="<?=$ar["CONTROL_NAME"]?>"
														id="<?=$ar["CONTROL_ID"]?>"
														<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
														onclick="smartFilter.click(this)"
														/>
													<label data-role="label_<?=$ar["CONTROL_ID"]?>" for="<?=$ar["CONTROL_ID"] ?>">
														<?=$ar["VALUE"]?>
													</label>
												
											<?endforeach;?>
											<?if (!empty($hiddenValues)) {?>
											<?#DebugMessage($hiddenValues,1111);?>
													<?foreach ($hiddenValues as $val => $ar):?>
															<input
																class="custom_checkbox"
																type="checkbox"
																value="<?=$ar["HTML_VALUE"]?>"
																name="<?=$ar["CONTROL_NAME"]?>"
																id="<?=$ar["CONTROL_ID"]?>"
																<? echo $ar["CHECKED"]? 'checked="checked"': '' ?>
																onclick="smartFilter.click(this)"
																/>
															<label data-role="label_<?=$ar["CONTROL_ID"]?>" for="<?=$ar["CONTROL_ID"] ?>">
																<?=$ar["VALUE"]?>
															</label>
													<?endforeach;?>
												
												<!--<a data-collapse="#hidden-values<?=$arItem['ID']?>" href="#" data-active-label="Скрыть" data-hidden-label="Показать еще">
													<span>Показать еще</span>
												</a>/-->
											<?}?>
											</div>
											<?if (!empty($hiddenValues)) {?>
											<a href="#" class="input-btn gray-btn all open-next">показать все</a>
											<?}?>
										</div>
								<?}?>
								<br/>
							</div>
<!--                            <a data-collapse="#show-me" href="#">-->
<!--                                <span class="collapse-text-show">Show +</span>-->
<!--                                <span class="collapse-text-hide">Hide -</span>-->
<!--                            </a>-->
<!--                            <div class="collapse" id="show-me">-->
<!--                                <p>Now you see me, now you don't.</p>-->
<!--                            </div>-->
						<?endif;?>
				<?
				}
				?>
	<?
		$arItem['DISPLAY_EXPANDED'] == 'Y' ? $expanded_fields_html .= ob_get_clean() : $collapsed_fields_html .= ob_get_clean();
	}
	?>
	<div class="filter-block">
		<div class="values_wrap clearfix">
		<?print($expanded_fields_html)?>
		</div>
	</div>
	<?/*if (!empty($collapsed_fields_html)) {?>
		<div class="fieldset collapse" id="hidden-props">
			<?print($collapsed_fields_html)?>
		</div>
		<a data-collapse="#hidden-props" href="#" data-active-label="Скрыть характеристики" data-hidden-label="Дополнительные характеристики">
			<span>Дополнительные характеристики123</span>
		</a>
	<?}*/?>

		<?foreach ($arResult["STOCK"] as $id => $name):?>
			<div class="filter-block">
				<div class="checkbox_wrap open-block">
					<input 
						type="checkbox" 
						name="stock[]" 
						id="ak_<?=$id?>" 
						value="<?=$id?>"
						 class="custom_checkbox" 
						<?if (in_array($id, $_REQUEST['stock'])):?> checked<?endif;?>
					/>
					<label for="ak_<?=$id?>">Участвует в акции «<?=$name?>»</label>
				</div>
			</div>
		<?endforeach;?>

		<!--<input class="empty-btn" type="submit" id="set_filter" name="set_filter" value="Показать" />/-->
		<input type="submit" class="input-btn gray-btn" value="Показать результаты" id="set_filter" name="set_filter" />
		<br />
		<br />
	</form>
</div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>');
	
	$(document).ready(function() {
		<?if (strlen($_GET["set_filter"]) > 0):?>
		$(".show-filter-btn").addClass("opened");
		$(".main-filter-form").addClass("opened");
		
		<?endif;?>
	});
</script>