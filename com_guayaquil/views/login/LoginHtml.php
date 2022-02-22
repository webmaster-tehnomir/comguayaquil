<?php
/**
 * Created by Laximo.
 * User: elnikov.a
 * Date: 27.08.18
 * Time: 14:04
 */

namespace guayaquil\views\login;

use guayaquil\View;


class LoginHtml extends View
{
    public function Display($tpl = 'login', $view = 'view')
    {
        $view = $this->input->getString('view');
        switch ($view) {
            case 'login':
                $this->login();
                break;
            case 'logout':
                $this->logout();
                break;
        }

    }

    public function login() {
        $user = $this->input->formData()['user'];

        if (!$user) {
            return;
        }
        $requests = [
            'appendListCatalogs' => []
        ];

        $login = trim($user['login']);
        $key   = $user['password'];

        $this->getData($requests, [],  $login, $key);
        if ($this->user) {
            $_SESSION['logged']   = true;
            $_SESSION['username'] = $login;
            $_SESSION['key']      = $key;
            $this->redirect($user['backurl'] . '&auth=true');
        } else {
            unset($_SESSION['logged']);
            unset($_SESSION['username']);
            unset($_SESSION['key']);
            $this->redirect($user['backurl'] . '&auth=false');
        }
    }

    public function logout() {
        unset($_SESSION['logged']);
        unset($_SESSION['username']);
        unset($_SESSION['key']);

        $data = $this->input->formData();

        if(!$_SESSION['logged']) {
            $this->redirect($data['user']['backurl']);
        }
    }
}