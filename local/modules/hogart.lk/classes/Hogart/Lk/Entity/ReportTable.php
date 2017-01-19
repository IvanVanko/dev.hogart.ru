<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/01/2017
 * Time: 17:26
 */

namespace Hogart\Lk\Entity;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Exchange\RabbitMQ\Consumer;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\ReportExchange;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\Message;
use Ramsey\Uuid\Uuid;

class ReportTable extends AbstractEntity
{
    const TYPE_PRICE = "price";
    const TYPE_STOCK = "stock";

    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_reports";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new StringField("name"),
            new StringField("type", [
                "required" => true,
                'validation' => [__CLASS__, 'validateType'],
            ]),
            new IntegerField("account_id", ['primary' => true]),
            new ReferenceField("account", __NAMESPACE__ . "\\AccountTable", ["=this.account_id" => "ref.id"]),
            new TextField("request"),
            new DatetimeField("created_at"),
            new StringField("path")
        ];
    }

    public static function validateType()
    {
        return [
            function ($value, $primary, array $row, Field $field) {
                if(in_array($value, [self::TYPE_STOCK, self::TYPE_PRICE])) {
                    return true;
                }
                return "Неизвестный тип {$value}";
            }
        ];
    }

    public static function getByAccountId($account_id, $filter = [])
    {
        return array_reduce(self::getList([
            'filter' => array_merge([
                '=account_id' => $account_id
            ], $filter),
            'order' => [
                'created_at' => 'desc'
            ]
        ])->fetchAll(), function ($result, $item) { $result[$item['guid_id']] = $item; return $result; }, []);
    }

    public static function generateReport($request)
    {
        global $DB;

        $stores = array_column(AccountStoreRelationTable::getByAccountId($request['accountId']), "XML_ID");
        $whereStores = "1=1";
        if (!empty($stores)) {
            $whereStores = "h_store_amount.store_guid IN ('" . implode("','", $stores) . "')";
        }
        $companies = AccountCompanyRelationTable::getByAccountId($request['accountId']);
        if (empty($request['company'])) {
            foreach ($companies as $company) {
                $request['company'][] = $company['id'];
            }
        }

        $catalogId = CATALOG_IBLOCK_ID;

        $typeText = self::getTypeText($request['report']);
        $orders = [
            'category' => "LEFT_MARGIN ASC",
            'brand' => "BRAND ASC"
        ];

        $order = "";
        $groups = [];
        if (!empty($request['groups'])) {
            $groups = explode(",", $request['groups']);
        }
        if (!empty($groups)) {
            $order = "ORDER BY ";
            $_order = [];
            foreach ($groups as $key) {
                $_order[] = $orders[$key];
            }
            $order .= implode(", ", $_order);
        }

        $having = "";
        if (!empty($request["in_stock"])) {
            $having = [];
            $having[] = "STOCK > 0";
        }

        if (!empty($having)) {
            $having = "HAVING " . implode(", ", $having);
        }

        $categoriesTmp = [];
        $categoriesResult = \CIBlockSection::GetList(['left_margin' => 'asc'], ['SITE_ID' => SITE_ID, 'IBLOCK_ID' => CATALOG_IBLOCK_ID, 'GLOBAL_ACTIVE' => 'Y'], false, ['ID', 'NAME', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID']);
        while ($category = $categoriesResult->GetNext()) {
            $parents = [];
            $path = [];
            if (!empty($categoriesTmp[$category['IBLOCK_SECTION_ID']]['PARENTS'])) {
                $parents = array_merge($parents, $categoriesTmp[$category['IBLOCK_SECTION_ID']]['PARENTS']);
            }
            if (!empty($categoriesTmp[$category['IBLOCK_SECTION_ID']]['PATH'])) {
                $path[] = $categoriesTmp[$category['IBLOCK_SECTION_ID']]['PATH'];
            }
            if (!empty($category['IBLOCK_SECTION_ID'])) {
                $parents[] = $category['IBLOCK_SECTION_ID'];
                $path[] = $categoriesTmp[$category['IBLOCK_SECTION_ID']]['NAME'];
            }

            $categoriesTmp[$category['ID']] = array_merge($category, [
                'PARENTS' => $parents,
                'PATH' => implode('/', $path)
            ]);
        }

        $maxLevel = max(array_column($categoriesTmp, 'DEPTH_LEVEL'));

        if (!empty($request['category'])) {

            $categories = [];
            foreach ($categoriesTmp as $item) {
                if ($item['DEPTH_LEVEL'] != $maxLevel || !in_array($item['ID'], $request['category'])) continue;
                $categories[] = $item['ID'];
                //  = array_merge($categories, array_merge([$item['ID']], in_array('category', $groups) ? $item['PARENTS'] : []));
            }

            $categories = "and bis.ID in (" . implode(', ', array_unique($categories)) . ")";
        }

        $brands = "";
        if (!empty($request['brand'])) {
            $brands = "and brand.ID IN (" . implode(", ", $request['brand']) . ")";
        }

        $sql =<<<SQL
select 
  bie.ID, 
  bis.NAME as CATEGORY_NAME, 
  bie.IBLOCK_SECTION_ID,
  bis.`DEPTH_LEVEL`, 
  bis.LEFT_MARGIN, 
  bie.NAME, 
  bie.DETAIL_TEXT, 
  bie.XML_ID, 
  sku.value as SKU, 
  brand.NAME as BRAND,
  concat('/upload/', b_file.SUBDIR, '/', b_file.FILE_NAME) as PREVIEW_IMAGE,
  IFNULL(sum(h_store_amount.stock), 0) as STOCK,
  b_catalog_price.PRICE
from b_iblock_section bis
left join b_iblock_element bie on (bis.ID = bie.IBLOCK_SECTION_ID and bie.IBLOCK_ID = $catalogId and bie.ACTIVE = 'Y')
left join b_file ON (b_file.ID = bie.PREVIEW_PICTURE)
left join b_iblock_element_property sku ON (bie.ID = sku.`IBLOCK_ELEMENT_ID` and sku.`IBLOCK_PROPERTY_ID` = (select ID from b_iblock_property where IBLOCK_ID = $catalogId and `CODE` = 'sku'))
left join b_iblock_element_property brandId ON (bie.ID = brandId.`IBLOCK_ELEMENT_ID` and brandId.`IBLOCK_PROPERTY_ID` = (select ID from b_iblock_property where IBLOCK_ID = $catalogId and `CODE` = 'brand'))
left join b_iblock_element brand ON (brandId.value = brand.ID)
left join b_catalog_price ON (b_catalog_price.PRODUCT_ID = bie.ID)
left join h_store_amount ON (h_store_amount.item_id = bie.ID and $whereStores)
where 1=1
	and bis.IBLOCK_ID = $catalogId
	and bie.ID IS NOT NULL
	$categories
	$brands
GROUP BY ID
$having
$order
SQL;

        $result = $DB->Query($sql);

        $brands = [];
        $items = [];
        while ($row = $result->GetNext()) {
            if (!empty($row['ID'])) {
                $brands[] = $row['BRAND'];
            }
            $items[] = $row;
            $prices[$row['ID']] = $row['PRICE'];
        }

        $groupsStyle = array(
            'alignment' => array(
                'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Компания Хогарт");
        $objPHPExcel->getProperties()->setLastModifiedBy("Компания Хогарт");
        $objPHPExcel->getProperties()->setTitle($typeText);
        $objPHPExcel->getProperties()->setSubject($typeText);
        $objPHPExcel->removeSheetByIndex();

        foreach ($request['company'] as $index => $companyId) {
            $company = $companies[$companyId];
            $companyName = CompanyTable::showName($company);
            if (empty($companyName)) {
                continue;
            }

            $sheet = $objPHPExcel->createSheet($index);
            $sheet->setTitle($companyName);

            $headerNames = [];

            if (!empty($request['image'])) {
                $headerNames["Изображение"] = \PHPExcel_Style_NumberFormat::FORMAT_GENERAL;
            }

            $headerNames = array_merge($headerNames, [
                "Артикул" => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                "Наименование" => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                "Категория" => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
                "Бренд" => \PHPExcel_Style_NumberFormat::FORMAT_TEXT,
            ]);

            switch ($request['report']) {
                case self::TYPE_PRICE:
                    $companyPrices = CompanyDiscountTable::getPricesByCompany($companyId, $prices);
                    $headerNames = array_merge($headerNames, [
                        "Цена" => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
                        "Макс. скидка" => \PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00,
                        "Цена со скидкой" => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1
                    ]);
                    break;
                case self::TYPE_STOCK:
                    $headerNames = array_merge($headerNames, [
                        "Остаток" => \PHPExcel_Style_NumberFormat::FORMAT_NUMBER,
                    ]);
                    break;
            }

            $headerNamesIndices = array_flip(array_keys($headerNames));

            foreach (array_keys($headerNames) as $indexHeaderNames => $headerName) {
                $sheet
                    ->setCellValueByColumnAndRow($indexHeaderNames, 1, $headerName)
                    ->getStyleByColumnAndRow($indexHeaderNames, 1, $indexHeaderNames, 1)
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true)
                ;

                $sheet
                    ->getColumnDimensionByColumn($indexHeaderNames)
                    ->setAutoSize(true)
                ;
            }

            if (!empty($request['image'])) {
                $sheet
                    ->getColumnDimension("A")
                    ->setWidth(20)
                    ->setAutoSize(false)
                ;
            }

            $number = 1;
            $currentCategory = null;
            $currentBrand = null;
            foreach ($items as $row) {
                $number++;

                if (in_array('brand', $groups) && array_search('brand', $groups) === 0 && $currentBrand != $row['BRAND']) {
                    $sheet
                        ->setCellValueByColumnAndRow(0, $number, $row['BRAND'])
                        ->mergeCellsByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                        ->getRowDimension($number)
                        ->setOutlineLevel(1)
                        ->setVisible(false)
                        ->setCollapsed(true)
                    ;
                    $sheet
                        ->getStyleByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                        ->applyFromArray($groupsStyle)
                        ->getFont()
                        ->setSize(16)
                        ->setBold(true)
                    ;
                    $currentBrand = $row['BRAND'];
                    $currentCategory = null;
                    $number++;
                }

                if (in_array('category', $groups) && $currentCategory != $row['IBLOCK_SECTION_ID']) {
                    foreach ($categoriesTmp[$row['IBLOCK_SECTION_ID']]['PARENTS'] as $k => $parentCategoryId) {
                        if (null !== $currentCategory && in_array($parentCategoryId, $categoriesTmp[$currentCategory]['PARENTS'])) continue;

                        $sheet
                            ->setCellValueByColumnAndRow(0, $number, $categoriesTmp[$parentCategoryId]['NAME'])
                            ->mergeCellsByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                            ->getRowDimension($number)
                            ->setOutlineLevel(($k + 1) + array_search('category', $groups))
                            ->setVisible(false)
                            ->setCollapsed(true)
                        ;
                        $sheet
                            ->getStyleByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                            ->applyFromArray($groupsStyle)
                            ->getFont()
                            ->setSize(20 - ($k * 2))
                            ->setBold(true)
                        ;
                        $number++;
                    }

                    $sheet
                        ->setCellValueByColumnAndRow(0, $number, $row['CATEGORY_NAME'])
                        ->mergeCellsByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                        ->getRowDimension($number)
                        ->setOutlineLevel($row['DEPTH_LEVEL'] + 1 + array_search('category', $groups))
                        ->setVisible(false)
                        ->setCollapsed(true)
                    ;
                    $sheet
                        ->getStyleByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                        ->applyFromArray($groupsStyle)
                        ->getFont()
                        ->setSize(12)
                        ->setBold(true)
                    ;
                    $currentCategory = $row['IBLOCK_SECTION_ID'];
                    $number++;
                }

                if (!empty($row['ID'])) {

                    if (in_array('brand', $groups) && $currentBrand != $row['BRAND']) {
                        $sheet
                            ->setCellValueByColumnAndRow(0, $number, $row['BRAND'])
                            ->mergeCellsByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                            ->getRowDimension($number)
                            ->setOutlineLevel($row['DEPTH_LEVEL'] + 1 + array_search('brand', $groups))
                            ->setVisible(false)
                            ->setCollapsed(true)
                        ;
                        $sheet
                            ->getStyleByColumnAndRow(0, $number, count($headerNamesIndices) - 1, $number)
                            ->applyFromArray($groupsStyle)
                            ->getFont()
                            ->setSize(12)
                            ->setBold(true)
                        ;
                        $currentBrand = $row['BRAND'];
                        $number++;
                    }

                    $sheet
                        ->setCellValueByColumnAndRow($headerNamesIndices["Артикул"], $number, $row['SKU'])
                        ->setCellValueByColumnAndRow($headerNamesIndices["Наименование"], $number, $row['NAME'])
                        ->setCellValueByColumnAndRow($headerNamesIndices["Категория"], $number, $categoriesTmp[$row['IBLOCK_SECTION_ID']]['PATH'] . '/' . $row['CATEGORY_NAME'])
                        ->setCellValueByColumnAndRow($headerNamesIndices["Бренд"], $number, $row['BRAND'])
                    ;

                    if (!empty($request['image']) && !empty($row['PREVIEW_IMAGE'])) {
                        $sheet
                            ->getRowDimension($number)
                            ->setRowHeight(150)
                        ;
                        $image = imagecreatefromstring(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $row['PREVIEW_IMAGE']));
                        $objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
                        $objDrawing->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
                        $objDrawing->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
                        $objDrawing->setImageResource($image);
                        $objDrawing->setResizeProportional(true);
                        $objDrawing->setWidthAndHeight(150, 150);
                        $objDrawing->setOffsetX(10);
                        $objDrawing->setOffsetY(10);
                        $objDrawing->setCoordinates("A" . $number);
                        $objDrawing->setWorksheet($sheet);
                    }

                    switch ($request['report']) {
                        case self::TYPE_PRICE:
                            $sheet
                                ->setCellValueByColumnAndRow($headerNamesIndices["Цена"], $number, $row['PRICE'])
                                ->setCellValueByColumnAndRow($headerNamesIndices["Макс. скидка"], $number, $companyPrices[$row['ID']]['max_discount'] / 100)
                                ->setCellValueByColumnAndRow($headerNamesIndices["Цена со скидкой"], $number, $companyPrices[$row['ID']]['price'])
                            ;
                            break;
                        case self::TYPE_STOCK:
                            $sheet
                                ->setCellValueByColumnAndRow($headerNamesIndices["Остаток"], $number, $row['STOCK'])
                            ;
                            break;
                    }

                    if (!empty($groups)) {
                        $sheet
                            ->getRowDimension($number)
                            ->setOutlineLevel(
                                in_array('category', $groups) ?
                                    $row['DEPTH_LEVEL'] + 2 + array_search('category', $groups)
                                    : 2
                            )
                            ->setVisible(false)
                            ->setCollapsed(true)
                        ;
                    }
                }
            }

            foreach (array_keys($headerNames) as $indexHeaderNames => $headerName) {
                $sheet
                    ->getStyleByColumnAndRow($indexHeaderNames, 1, $indexHeaderNames, $number)
                    ->getNumberFormat()
                    ->setFormatCode($headerNames[$headerName])
                ;
            }
        }

        foreach ($objPHPExcel->getAllSheets() as $allSheet) {
            var_dump($allSheet->getTitle());
        }
        var_dump($request['company'], count($objPHPExcel->getAllSheets()));
        exit;

        $writer = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $guid = Uuid::uuid1()->toString();
        $path = HOGART_REPORTS_DIR . "/{$guid}.xlsx";
        $writer->save($path);

        return parent::add([
            'account_id' => $request['accountId'],
            'guid_id' => $guid,
            'name' => $typeText,
            'request' => serialize($request),
            'type' => $request['report'],
            'path' => $path
        ]);

    }

    public static function reportRequest($accountId, $data)
    {
        $data['accountId'] = $accountId;
        $exchange = new ReportExchange();
        $exchange->useConsumer(Consumer::getInstance());
        $exchange->publish(serialize($data), 'request');
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $result->modifyFields([
            'created_at' => new DateTime(),
        ]);
        return $result;
    }

    public static function getFilename($id)
    {
        $file = self::getByField("guid_id", $id);
        return "Hogart_" . $file['name'] . "_" . $file['created_at']->format("Y-m-d-H-i-s");
    }

    public static function getTitle($id)
    {
        $file = self::getByField("guid_id", $id);
        return $file['name'] . " от " . $file['created_at']->format("Y/m/d H:i:s");
    }

    public static function onAfterAdd(Event $event)
    {
        $id = $event->getParameter('primary')['guid_id'];
        $fields = $event->getParameter('fields');
        $message = new Message(
            self::getTitle($id) . " <b>(скачать)</b>",
            Message::SEVERITY_INFO
        );
        $message
            ->setIcon('fa fa-file-text-o')
            ->setUrl("/account/reports/get/" . $fields['account_id'] . "/" . $id)
            ->setDelay(0)
        ;
        FlashMessagesTable::addNewMessage($fields['account_id'], $message);
    }

    /**
     * @param $type
     * @return mixed
     */
    public static function getTypeText($type)
    {
        return [
            self::TYPE_PRICE => "Прайс-лист",
            self::TYPE_STOCK => "Отчет по остаткам",
        ][$type];
    }
}
