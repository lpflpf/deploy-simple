<?php
/**
 * IndexController.php
 *
 * @author   <lipengfeijs@dangdang.com>
 * @created  2017/8/2 11:41
 */

namespace Deploy\Controller;


use Deploy\Model\User;

class IndexController extends Controller
{
    public function login()
    {
        $this->render('login');
    }

    public function doLogin()
    {
        $user = $_POST['username'];
        $password = $_POST['password'];

        session_id() === '' && session_start();
        if ($userId = User::isValidUser($user, $password)){
            $_SESSION['username'] = $user;
            $_SESSION['userid'] = $userId;
            header('Location:/deploy/index');
        }
        $this->render('login', [
            'status' => 1,
            'message' => '登录验证失败',
        ]);
    }

    public function exit(){
        unset($_SESSION['username']);
        unset($_SESSION['userid']);
        $this->render('login');
    }
}