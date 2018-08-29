<?php
use GuzzleHttp\Client as GuzzClient;
use GuzzleHttp\Exception\GuzzleException;

/**
 * 
 * @author aipacific.vn
 *
 */
class Remote
{
    /**
     * @param string $uri
     * @param array $params
     * @param string $method
     * @param array $headers
     * @return mixed|boolean
     */
    public static function curl($uri, $params = [], $method = 'POST', $headers = []) 
    {
        $options = array_merge(['headers' => $headers], $params);
        
        try {
            $guzzClient     = new GuzzClient();
            $res            = $guzzClient->request($method, $uri, $options);
            
            
            $responseCode   = $res->getStatusCode();
            
            if($responseCode == 200) {
                $bodyContents   = $res->getBody()->getContents();
                $contents       = json_decode($bodyContents);
                
                //bắt lỗi json
                if (($jsonError = json_last_error())) {
                    switch ($jsonError) {
                        case JSON_ERROR_NONE:
                            //No errors
                            break;
                        case JSON_ERROR_DEPTH:
                            //Maximum stack depth exceeded
                            break;
                        case JSON_ERROR_STATE_MISMATCH:
                            //Underflow or the modes mismatch
                            break;
                        case JSON_ERROR_CTRL_CHAR:
                            //Unexpected control character found
                            break;
                        case JSON_ERROR_SYNTAX:
                            //Syntax error, malformed JSON
                            break;
                        case JSON_ERROR_UTF8:
                            //Malformed UTF-8 characters, possibly incorrectly encoded
                            break;
                        default:
                            //Unknown error
                            break;
                    }
                }
                
                //trả về nội dung
                return $contents;
            }
        } catch (GuzzleException $e) {
            //lỗi truy vấn
        }
    }
    
    /**
     * @param string $uri
     * @param string $userToken
     * @return mixed|boolean
     */
    public static function uinfo($uri, $userToken, $appToken)
    {
        $headers    = array();
        $params     = array();
        
        //gắn user-token vào header
        $headers['Authorization'] = 'Bearer ' . $userToken;
        
        //gắn app-token vào request
        $params['form_params']['AppToken'] = $appToken;
        
        return self::curl($uri, $params, 'POST', $headers);
    }
}