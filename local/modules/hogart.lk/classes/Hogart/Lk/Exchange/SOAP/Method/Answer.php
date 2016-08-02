<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/08/16
 * Time: 12:47
 */

namespace Hogart\Lk\Exchange\SOAP\Method;


class Answer extends Request
{
    /** @var  AnswerObject[] */
    public $Answer = [];

    public function addAnswer(AnswerObject $answerObject)
    {
        $this->Answer[] = $answerObject;

        return $this;
    }
}