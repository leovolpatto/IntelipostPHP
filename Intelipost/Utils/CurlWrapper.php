<?php

namespace Intelipost\Utils;

final class CurlWrapper {

    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    protected $_url;
    protected $_followlocation = true;
    protected $_timeout = 30;
    protected $_maxRedirects = 4;
    protected $_cookieFileLocation;
    protected $_post;
    protected $_put;
    protected $_postFields;
    protected $_customRequest;
    protected $_referer = "http://www.google.com";
    protected $_session;
    protected $_result;
    protected $_includeHeader = false;
    protected $_returnTransfer = true;
    protected $_httpHeaders = array();
    protected $_noBody = false;
    protected $_status;
    protected $_fullStatus;
    protected $_binaryTransfer = false;
    protected $_authentication = 0;
    protected $_auth_name = '';
    protected $_auth_pass = '';
    protected $_ssl_verify_peer = false;
    protected $_encoding = '';

    public function UseAuth($use) {
        $this->_authentication = 0;

        if ($use == true) {
            $this->_authentication = 1;
        }
    }

    public function SetName($name) {
        $this->_auth_name = $name;
    }

    public function SetPass($pass) {
        $this->_auth_pass = $pass;
    }

    public function SetSSLVerifyPeer($flag) {
        $this->_ssl_verify_peer = $flag;
    }

    public function __construct($customRequest) {
        $this->_customRequest = $customRequest;
        $this->_cookieFileLocation = dirname(__FILE__) . '/cookie.txt';
        $this->_httpHeaders = array();
    }

    public function SetReferer($referer) {
        $this->_referer = $referer;
    }

    public function SetCookiFileLocation($path) {
        $this->_cookieFileLocation = $path;
    }

    public function SetIncludeHeader($include = true) {
        $this->_includeHeader = $include;
    }

    public function SetReturnTransfer($return = true) {
        $this->_returnTransfer = $return;
    }

    public function SetPost($postFields) {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    public function SetPut($putFields) {
        $this->_put = true;
        $this->_postFields = $putFields;
    }

    public function SetCustomRequest($method) {
        $this->_customRequest = $method;
    }

    public function SetUserAgent($userAgent) {
        $this->_useragent = $userAgent;
    }

    public function SetHttpHeaders($httpHeader) {
        array_push($this->_httpHeaders, $httpHeader);
    }

    public function FollowLocation($follow = true) {
        $this->_followlocation = $follow;
    }

    public function SetEnconding($enc) {
        $this->_encoding = $enc;
    }

    public function CreateCurl($url = 'nul', $showErrors = false) {
        if ($url != 'nul') {
            $this->_url = $url;
        } else {
            die('Url missing!');
        }

        $s = curl_init();

        curl_setopt($s, CURLOPT_URL, $this->_url);
        curl_setopt($s, CURLOPT_HTTPHEADER, $this->_httpHeaders);
        curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
        curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, $this->_returnTransfer);
        curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
        curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
        curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, $this->_ssl_verify_peer);

        if ($this->_authentication == 1) {
            curl_setopt($s, CURLOPT_USERPWD, $this->_auth_name . ':' . $this->_auth_pass);
        }

        if ($this->_post) {
            curl_setopt($s, CURLOPT_POST, 1);
            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
        } elseif ($this->_put) {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_customRequest);
            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
        } else {
            curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_customRequest);
        }

        if ($this->_includeHeader) {
            curl_setopt($s, CURLOPT_HEADER, true);
        }

        if ($this->_encoding != '')
            curl_setopt($s, CURLOPT_ENCODING, $this->_encoding);

        if ($this->_noBody) {
            curl_setopt($s, CURLOPT_NOBODY, true);
        }

        if ($this->_binaryTransfer) {
            curl_setopt($s, CURLOPT_BINARYTRANSFER, true);
        }

        curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
        curl_setopt($s, CURLOPT_REFERER, $this->_referer);

        $this->_result = curl_exec($s);
        if ($this->_result === false) {
            $error = curl_errno($s);
            $traceItem = array(
                'errorno' => curl_errno($s),
                'error' => curl_error($s),
            );
            if ($error == CURLE_SSL_PEER_CERTIFICATE || $error == CURLE_SSL_CACERT || $error == 77) {
                curl_setopt($s, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($s, CURLOPT_CAINFO, SYSTEM_DIR . 'integracoesProxies/util/certificados/ca-bundle.crt');
                //curl_setopt($s,CURLOPT_CAINFO,'mozilla.pem'); /* fixed! */
                //debug(SYSTEM_DIR . 'integracoesProxies/util/certificados/certificado_kanlo_com_br.crt');
                $this->_result = curl_exec($s);
                $traceItem = array(
                    'errorno' => curl_errno($s),
                    'error' => curl_error($s),
                );
            }
        }
        $this->_status = curl_getinfo($s, CURLINFO_HTTP_CODE);
        $this->_fullStatus = curl_getinfo($s);

// =============== TESTE =================================================================== //
// 
//        $res = $this->curl_trace_redirects("https://www.cremme.com.br/commerce-api/api");
//        foreach ($res as $item) {
//            if (isset($item['timeout'])) {
//                debug("Timeout reached!");
//            } else if (isset($item['error'])) {
//                debug("error: " . $item['error'] . "");
//            } else {
//                debug($item['url']);
//                if (!empty($item['redirect_url'])) {
//                    // redirection
//                    debug(" -> (" . $item['http_code'] . ")");
//                }
//            }
//        }
// ===========================================================================================

        if ($showErrors && !$this->_result) {
            trigger_error(curl_error($s));
        }

        mail('adilo@eccosys.com.br', 'trace do curl marketplaces', print_r($this, true));
        curl_close($s);
    }

    public function GetFullStatus() {
        return $this->_fullStatus;
    }

    public function GetHttpStatus() {
        return $this->_status;
    }

    public function GetResult() {
        return $this->_result;
    }

    /**
     * 
     * @return string
     */
    public function GetCustomRequest() {
        return $this->_customRequest;
    }

    /**
     * 
     * @return string
     */
    public function GetURL() {
        return $this->_url;
    }

// ==== FUNCTION TESTE =================================================== //
//    public function curl_trace_redirects($url, $timeout = 15) {
//
//        $result = array();
//        $s = curl_init();
//
//        $trace = true;
//        $currentUrl = $url;
//
//        $urlHist = array();
//        while ($trace && $timeout > 0 && !isset($urlHist[$currentUrl])) {
//            $urlHist[$currentUrl] = true;
//
//                    curl_setopt($s, CURLOPT_URL, $currentUrl);
//        curl_setopt($s, CURLOPT_HTTPHEADER, $this->_httpHeaders);
//        curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
//        curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
//        curl_setopt($s, CURLOPT_RETURNTRANSFER, $this->_returnTransfer);
//        curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
//        curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
//        curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
//       // curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
//
//        if ($this->_authentication == 1) {
//            curl_setopt($s, CURLOPT_USERPWD, $this->_auth_name . ':' . $this->_auth_pass);
//        }
//
//        if ($this->_post) {
//            curl_setopt($s, CURLOPT_POST, 1);
//            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
//        } elseif ($this->_put) {
//            curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_customRequest);
//            curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
//        } else {
//            curl_setopt($s, CURLOPT_CUSTOMREQUEST, $this->_customRequest);
//        }
//
//        if ($this->_includeHeader) {
//            curl_setopt($s, CURLOPT_HEADER, true);
//        }
//
//        if ($this->_encoding != '')
//            curl_setopt($s, CURLOPT_ENCODING, $this->_encoding);
//
//        if ($this->_noBody) {
//            curl_setopt($s, CURLOPT_NOBODY, true);
//        }
//
//        if ($this->_binaryTransfer) {
//            curl_setopt($s, CURLOPT_BINARYTRANSFER, true);
//        }
//
//        curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
//        curl_setopt($s, CURLOPT_REFERER, $this->_referer);
//
//            $output = curl_exec($s);
//
//            if ($output === false) {
//                $traceItem = array(
//                    'errorno' => curl_errno($s),
//                    'error' => curl_error($s),
//                );
//                            debug($traceItem);
//                $trace = false;
//            } else {
//                $curlinfo = curl_getinfo($s);
//
//                if (isset($curlinfo['total_time'])) {
//                    $timeout -= $curlinfo['total_time'];
//                }
//
//                if (!isset($curlinfo['redirect_url'])) {
//                    $curlinfo['redirect_url'] = get_redirect_url($output);
//                }
//
//                if (!empty($curlinfo['redirect_url'])) {
//                    $currentUrl = $curlinfo['redirect_url'];
//                } else {
//                    $trace = false;
//                }
//
//                $traceItem = $curlinfo;
//            }
//
//            $result[] = $traceItem;
//        }
//
//        if ($timeout < 0) {
//            $result[] = array('timeout' => $timeout);
//        }
//
//        curl_close($s);
//
//        return $result;
//    }
// =================================================================================
}
