<?
namespace Bitrix\Highloadblock;

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\DB\MssqlConnection;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;


class CustomHighloadBlockTable extends HighloadBlockTable {

    public static function compileEntity($hlblock)
    {
        // generate entity & data manager
        $fieldsMap = array();

        // add ID
        $fieldsMap['ID'] = array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true
        );

        // add other fields
        $fields = $GLOBALS['USER_FIELD_MANAGER']->getUserFields('HLBLOCK_'.$hlblock['ID']);
        foreach ($fields as $field)
        {
            $fieldsMap[$field['FIELD_NAME']] = array(
                'data_type' => Entity\UField::convertBaseTypeToDataType($field['USER_TYPE']['BASE_TYPE'])
            );
        }

        // build classes
        $entity_name = $hlblock['NAME'];
        $entity_data_class = $hlblock['NAME'];

        if (!class_exists($entity_data_class.'Table'))
        {
            if (!preg_match('/^[a-z0-9_]+$/i', $entity_data_class))
            {
                throw new \Exception(sprintf(
                    'Invalid entity name `%s`.', $entity_data_class
                ));
            }

            $entity_table_name = $hlblock['TABLE_NAME'];

            $eval = '
				class '.$entity_data_class.'Table extends '.__NAMESPACE__.'\DataManager
				{
					public static function getFilePath()
					{
						return __FILE__;
					}

					public static function getTableName()
					{
						return '.var_export($entity_table_name, true).';
					}

					public static function getMap()
					{
						return '.var_export($fieldsMap, true).';
					}
				}
			';
            eval($eval);
        }

        return Entity\Base::getInstance($entity_name);
    }
}
?>