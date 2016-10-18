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
        if (version_compare(PHP_VERSION, "5.6.0") >= 0) {
            ini_set('default_socket_timeout', 600);
            $context = stream_context_create(array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            ));
            $this->config = array_merge($this->config, [
                'stream_context' => $context
            ]);
        }
        return $this->config;
    }
}
?>