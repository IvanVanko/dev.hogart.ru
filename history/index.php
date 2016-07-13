<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Истории успеха");
$APPLICATION->SetTitle("Истории успеха");
?>
<div class="row">
	<div class="col-md-9">
		<?
		$APPLICATION->IncludeComponent(
			"kontora:element.list",
			"history",
			Array(
				"IBLOCK_ID" => 14,
				'ORDER'     => array('sort' => 'asc'),
				'PROPS'     => 'Y',
				"CACHE_GROUPS" => "N",
			));
		?>
	</div>
	<div class="col-md-3 aside">
		<?
		$APPLICATION->IncludeComponent(
			"kontora:element.list",
			"jobs-list",
			Array(
				"IBLOCK_ID" => 4,
				'ORDER'     => array('sort' => 'asc'),
				'PROPS'     => 'Y',
			));
		?>
	</div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>