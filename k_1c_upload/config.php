<?
class SoapLocalConfig {
    private $url = _1C_WSDL_URL;
    private $config =
        array(
            'cache_wsdl' => WSDL_CACHE_NONE,
            'login'      => _1C_LOGIN,
            'password'   => _1C_PASSWORD,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS
        );

    public function getUrl () {
        return $this->url;
    }
    public function getConfig () {
        return $this->config;
    }
}
?>