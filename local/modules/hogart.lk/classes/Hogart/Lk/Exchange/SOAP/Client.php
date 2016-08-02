<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 31/07/16
 * Time: 01:01
 */

namespace Hogart\Lk\Exchange\SOAP;


use Hogart\Lk\Creational\Singleton;
use Hogart\Lk\Exchange\SOAP\Method\Account;

/**
 * Class Client
 * @package Hogart\Lk\Exchange\SOAP
 *
 * @property Account $Account
 */
class Client
{
    use Singleton;
    /** @var \SoapClient */
    protected $client;
    /** @var  MethodInterface[] */
    protected $methods = [];

    /**
     * Client constructor.
     */
    protected function create()
    {
        $scheme = \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_SCHEME");
        $host = \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_HOST");
        $port = \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_PORT");
        $endpoint = \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_ENDPOINT");
        $wsdl = "{$scheme}://{$host}:{$port}{$endpoint}?wsdl";
        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        ));
        $options = [
            'trace' => true,
            'keep_alive' => true,
            'stream_context' => $context,
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login'      => \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_LOGIN"),
            'password'   => \COption::GetOptionString("hogart.lk", "SOAP_SERVICE_PASSWORD"),
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        ];
        $this->client = new \SoapClient($wsdl, $options);
        $this->defaultMethodsRegister();
    }

    /**
     * @return \SoapClient
     */
    public function getClient()
    {
        return $this->client;
    }

    protected function defaultMethodsRegister()
    {
        $this->registerMethod(new Account());
    }

    /**
     * @param MethodInterface|MethodInterface[] $methods
     * @return $this
     */
    protected function registerMethod($methods)
    {
        if (is_object($methods)) $methods = [$methods];
        foreach ($methods as $method) {
            if (isset($this->methods[$method->getName()]) && get_class($method) !== get_class($this->methods[$method->getName()])) {
                throw new \RuntimeException("Duplicate method name!");
            }
            $this->methods[$method->getName()] = $method->useSoapClient($this->client);
        }

        return $this;
    }

    /**
     * @param MethodInterface $method
     * @return $this
     */
    protected function unregisterMethod(MethodInterface $method)
    {
        if (isset($this->methods[$method->getName()]) && get_class($method) !== get_class($this->methods[$method->getName()])) {
            throw new \RuntimeException("Duplicate method name!");
        }
        unset($this->methods[$method->getName()]);

        return $this;
    }

    /**
     * @param $name
     * @return MethodInterface
     */
    function __get($name)
    {
        if (isset($this->methods[$name])) return $this->methods[$name];
        throw new \RuntimeException("No method {$name}");
    }
}
