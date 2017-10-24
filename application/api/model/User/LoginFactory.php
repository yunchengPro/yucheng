<?php
namespace app\api\model\User;


use app\model\User\LoginModel;
use app\model\User\RegisterModel;

// use app\model\User\LoginModel;


class LoginFactory {
    public static function loginRegister($isUser) {
//         if(is_array($isUser)) {
//             return new LoginModel();
//         } else {
//             return new RegisterModel();
//         }

        if(is_array($isUser)) {
            return new LoginModel();
        } else {
            return new RegisterModel();
        }
    }
}