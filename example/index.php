<?php
/**
 * source code ví dụ thực hiện đăng nhập và chứng thực thông qua SSO và JWT của AI Pacific
 * địa chỉ test của SSO và JWT lần lượt là: http://tsso.aipacific.tech và http://tjwt.aipacific.tech
 * địa chỉ production của SSO và JWT lần lượt là https://id.aipacific.vn và https://jwt.aipacific.vn
 * 
 * trong ví dụ này giả sử ứng dụng web cần thực hiện chứng thực tại địa chỉa abc.com
 */
require_once('vendor/autoload.php');

$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : null;

if(!$token) {
    //chạy đi sso
    $callback   = base64_encode('abc.com/home');
    $redirect   = base64_encode('abc.com/dashboard');
    
    header("location: https://id.aipacific.vn/sso/SSO?_act=authen&redirect=$redirect&callback=$callback");
    exit;
} else {
    //lấy thông tin user
    $authenUri          = 'https://jwt.aipacific.vn/auth/auth?_mod=auth&_act=user&_renderer=module';
    $appToken           = 'app-token được cấp';
    
    $userInfo = Remote::uinfo($authenUri, $token, $appToken);
    
    var_dump($userInfo);
}