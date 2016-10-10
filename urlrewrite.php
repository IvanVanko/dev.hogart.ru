<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/documentation/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "/documentation/detail.php",
	),
	array(
		"CONDITION" => "#^/en/integrated-solutions/([\\w-_]+)/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "section=\$1&ELEMENT_CODE=\$2",
		"ID" => "",
		"PATH" => "/en/integrated-solutions/detail.php",
	),
	array(
		"CONDITION" => "#^/integrated-solutions/([\\w-_]+)/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "section=\$1&ELEMENT_CODE=\$2",
		"ID" => "",
		"PATH" => "/integrated-solutions/detail.php",
	),
	array(
		"CONDITION" => "#^/en/integrated-solutions/zones/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "zone=\$1",
		"ID" => "",
		"PATH" => "/en/integrated-solutions/zones.php",
	),
	array(
		"CONDITION" => "#^/integrated-solutions/zones/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "zone=\$1",
		"ID" => "",
		"PATH" => "/integrated-solutions/zones.php",
	),
	array(
		"CONDITION" => "#^/en/integrated-solutions/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => "",
		"PATH" => "/en/integrated-solutions/section_detail.php",
	),
	array(
		"CONDITION" => "#^/integrated-solutions/([\\w-_]+)/(\\?.*|\$)#",
		"RULE" => "SECTION_CODE=\$1",
		"ID" => "",
		"PATH" => "/integrated-solutions/section_detail.php",
	),
	array(
		"CONDITION" => "#^/brands/([A-z0-9_-]+)/(.+)/.*#",
		"RULE" => "&BRAND_CODE=\$1&SECTION_CODE=\$2",
		"ID" => "bitrix:catalog",
		"PATH" => "/brands/catalog.php",
	),
	array(
		"CONDITION" => "#^/en/learn/archive-seminarov/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/learn/archive-seminarov/index.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/learn/archive-seminarov/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/learn/archive-seminarov/index.php",
	),
	array(
		"CONDITION" => "#^/en/integrated-solutions/#",
		"RULE" => "",
		"ID" => "kontora:section.list",
		"PATH" => "/en/integrated-solutions/index.php",
	),
	array(
		"CONDITION" => "#^/en/helpful-information/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/helpful-information/index.php",
	),
	array(
		"CONDITION" => "#^/integrated-solutions/#",
		"RULE" => "",
		"ID" => "kontora:section.list",
		"PATH" => "/integrated-solutions/index.php",
	),
	array(
		"CONDITION" => "#^/integrated-solutions/#",
		"RULE" => "",
		"ID" => "kontora:element.list",
		"PATH" => "/local/templates/hogart/components/bitrix/news.detail/hogart_project_detail/template.php",
	),
	array(
		"CONDITION" => "#^/helpful-information/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/helpful-information/index.php",
	),
	array(
		"CONDITION" => "#^/en/learn/(.+)/.*#",
		"RULE" => "/en/learn/detail.php?ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "",
	),
	array(
		"CONDITION" => "#^/en/company/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/company/news/index.php",
	),
	array(
		"CONDITION" => "#^/learn/(.+)/.*#",
		"RULE" => "/learn/detail.php?ELEMENT_CODE=\$1",
		"ID" => "",
		"PATH" => "",
	),
	array(
		"CONDITION" => "#^/company/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/news/index.php",
	),
	array(
		"CONDITION" => "#^/company/jobs/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/jobs/index.php",
	),
	array(
		"CONDITION" => "#^/en/contacts/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/contacts/index.php",
	),
	array(
		"CONDITION" => "#^/en/brands/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/brands/index.php",
	),
	array(
		"CONDITION" => "#^/contacts/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/contacts/index.php",
	),
	array(
		"CONDITION" => "#^/en/stock/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/en/stock/index.php",
	),
	array(
		"CONDITION" => "#^/catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/brands/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/brands/index.php",
	),
	array(
		"CONDITION" => "#^/events/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/events/index.php",
	),
	array(
		"CONDITION" => "#^/learn/#",
		"RULE" => "",
		"ID" => "bitrix:news.list",
		"PATH" => "/learn/index.php",
	),
	array(
		"CONDITION" => "#^/stock/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/stock/index.php",
	),
	array(
		"CONDITION" => "#^/account/documents/?#",
		"RULE" => "",
		"ID" => "hogart.lk:account.documents",
		"PATH" => "/local/modules/hogart.lk/front/account/documents/index.php"
	),
    array(
        "CONDITION" => "#^/account/settings/?#",
        "RULE" => "",
        "ID" => "hogart.lk:account.settings",
        "PATH" => "/local/modules/hogart.lk/front/account/settings/index.php"
    ),
    array(
        "CONDITION" => "#^/account/cart/?#",
        "RULE" => "",
        "ID" => "hogart.lk:account.cart.list",
        "PATH" => "/local/modules/hogart.lk/front/account/cart/index.php"
    ),
    array(
        "CONDITION" => "#^/account/orders/pdf/([^/?]*)/([^/?]*).?#",
        "RULE" => "order=\$1&pdf=\$2",
        "ID" => "hogart.lk:account.order.pdf",
        "PATH" => "/local/modules/hogart.lk/front/account/orders/pdf.php"
    ),
    array(
        "CONDITION" => "#^/account/orders/shipment/([^/?]*).?#",
        "RULE" => "store=\$1",
        "ID" => "hogart.lk:account.order.shipment",
        "PATH" => "/local/modules/hogart.lk/front/account/orders/shipment.php"
    ),
    array(
        "CONDITION" => "#^/account/order/([0-9]+)/history/?#",
        "RULE" => "order=\$1",
        "ID" => "hogart.lk:account.order.history",
        "PATH" => "/local/modules/hogart.lk/front/account/orders/history.php"
    ),
    array(
        "CONDITION" => "#^/account/order/([^/?]*)[?/]?(.*)#",
        "RULE" => "order=\$1",
        "ID" => "hogart.lk:account.order",
        "PATH" => "/local/modules/hogart.lk/front/account/orders/order.php"
    ),
    array(
        "CONDITION" => "#^/account/orders/([^/?]*)[?/]?(.*)#",
        "RULE" => "/local/modules/hogart.lk/front/account/orders/index.php?state=\$1",
        "ID" => "hogart.lk:account.orders",
        "PATH" => ""
    ),
	array(
		"CONDITION" => "#^/account/?#",
		"RULE" => "",
		"ID" => "hogart.lk:account",
		"PATH" => "/local/modules/hogart.lk/front/account/index.php"
	)
);

?>