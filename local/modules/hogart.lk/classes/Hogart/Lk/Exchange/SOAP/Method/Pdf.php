<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 19:25
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


use Hogart\Lk\Entity\AbstractEntity;
use Hogart\Lk\Entity\PdfTable;
use Hogart\Lk\Exchange\SOAP\AbstractMethod;
use Hogart\Lk\Exchange\SOAP\Request\Pdf as Request;

class Pdf extends AbstractMethod
{
    /**
     * @inheritDoc
     */
    function getName()
    {
        return "Pdf";
    }

    public function getPdf(Request $request)
    {
        $response = $this->client->getSoapClient()->BillGet($request->request());
        if (!empty($response->return)) {
            /** @var AbstractEntity $class */
            $class = PdfTable::$types[$request->type];
            $entity = $class::getByField('guid_id', $request->ID);
            PdfTable::add([
                'data' => base64_decode($response->return),
                'type' => $request->type,
                'entity_id' => $entity['id']
            ]);
        }
        return $response;
    }
}