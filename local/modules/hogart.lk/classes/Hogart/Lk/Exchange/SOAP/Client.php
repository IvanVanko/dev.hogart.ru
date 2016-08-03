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
use Hogart\Lk\Exchange\SOAP\Method\Address;
use Hogart\Lk\Exchange\SOAP\Method\Contact;
use Hogart\Lk\Exchange\SOAP\Method\Contract;
use Hogart\Lk\Exchange\SOAP\Method\ContactInfo;
use Hogart\Lk\Exchange\SOAP\Method\HogartCompany;
use Hogart\Lk\Exchange\SOAP\Method\Staff;
use Hogart\Lk\Exchange\SOAP\Method\Company;
use Hogart\Lk\Exchange\SOAP\Method\PaymentAccount;
use Hogart\Lk\Logger\BitrixLogger;
use Hogart\Lk\Logger\FileLogger;
use Hogart\Lk\Logger\LoggerCollection;
use Hogart\Lk\Logger\LoggerInterface;

/**
 * Class Client
 * @package Hogart\Lk\Exchange\SOAP
 *
 * @property Account $Account
 * @property Address $Address
 * @property Company $Company
 * @property HogartCompany $HogartCompany
 * @property Staff $Staff
 * @property Contact $Contact
 * @property ContactInfo $ContactInfo
 * @property Contract $Contract
 * @property PaymentAccount $PaymentAccount
 */
class Client
{
    use Singleton;
    /** @var \SoapClient */
    protected $soapClient;
    /** @var  MethodInterface[] */
    protected $methods = [];
    /** @var  LoggerCollection */
    protected $logger;

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
        $this->soapClient = new \SoapClient($wsdl, $options);
        $this->logger = new LoggerCollection("SOAP-SERVICE", new BitrixLogger());
        $this->defaultMethodsRegister();
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function registerLogger(LoggerInterface $logger)
    {
        $this->logger->registerLogger($logger);

        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function unregisterLogger(LoggerInterface $logger)
    {
        $this->logger->unregisterLogger($logger);

        return $this;
    }

    /**
     * @return LoggerCollection
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @return \SoapClient
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }

    protected function defaultMethodsRegister()
    {
        $this->registerMethod(new Account());
        $this->registerMethod(new Address());
        $this->registerMethod(new Company());
        $this->registerMethod(new HogartCompany());
        $this->registerMethod(new Staff());
        $this->registerMethod(new Contact());
        $this->registerMethod(new ContactInfo());
        $this->registerMethod(new Contract());
        $this->registerMethod(new PaymentAccount());
    }

    /**
     * @param MethodInterface|MethodInterface[] $methods
     * @return $this
     */
    public function registerMethod($methods)
    {
        if (is_object($methods)) $methods = [$methods];
        foreach ($methods as $method) {
            if (isset($this->methods[$method->getName()]) && get_class($method) !== get_class($this->methods[$method->getName()])) {
                throw new \RuntimeException("Duplicate method name!");
            }
            $this->methods[$method->getName()] = $method->useSoapClient($this);
        }

        return $this;
    }

    /**
     * @param MethodInterface $method
     * @return $this
     */
    public function unregisterMethod(MethodInterface $method)
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
