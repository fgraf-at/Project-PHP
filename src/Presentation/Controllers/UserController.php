<?php

namespace Presentation\Controllers;

use Presentation\MVC\ActionResult;

class UserController extends \Presentation\MVC\Controller
{
    const PARAM_USER_NAME = 'un';
    const PARAM_PASSWORD = 'pwd';

    public function __construct(
        private \Application\SignedInUserQuery $signedInUserQuery,
        private \Application\Commands\RegisterCommand $registerCommand,
        private \Application\Commands\SignOutCommand $signOutCommand,
        private \Application\Commands\SignInCommand $signInCommand
    ) {
    }

    public function GET_LogIn(): \Presentation\MVC\ActionResult
    {
        $user = $this->signedInUserQuery->execute();
        if ($user != null) {
            return $this->redirect('Home', 'Index');
        }
        return $this->view('login', [
            'user' => $user,
            'userName' => $this->tryGetParam(self::PARAM_USER_NAME, $value) ? $value : ''
        ]);
    }

    public function POST_LogIn(): \Presentation\MVC\ActionResult
    {
        if (!$this->signInCommand->execute($this->getParam(self::PARAM_USER_NAME), $this->getParam(self::PARAM_PASSWORD))) {
            return $this->view('login', [
                'user' => $this->signedInUserQuery->execute(),
                'userName' =>  $this->getParam(self::PARAM_USER_NAME),
                'errors' => ['Invalid user name or password.']
            ]);
        }
        return $this->redirect('Home', 'Index');
    }

    public function POST_LogOut(): \Presentation\MVC\ActionResult
    {
        $this->signOutCommand->execute();
        return $this->redirect('Home', 'Index');
    }


    public function GET_Register(): \Presentation\MVC\ViewResult
    {
      return $this->view("registerNewUser", [
          "userName" => null,
          "password" => null,
          "user"     => null,
          "errors"   => null,
        ]);
    }
    public function POST_Register(): ActionResult
    {
        $userName = $this->getParam("userName");
        $password = $this->getParam("password");
        $result = $this->registerCommand->execute(
                                            $userName,
                                            $password
                                        );
        if($result != null) {
            $errors = [];
            return $this->view("registerNewUser", [
                "userName"  => $userName,
                "password"  => $password,
                "errors"    => [$result]
            ]);
        }
        else{
            //sign in if successful
            $signInSuccessful = $this->signInCommand->execute($userName, $password);
            if(!$signInSuccessful) {
                return $this->view("registerNewUser", [
                    "userName" => $userName,
                    "password" => $password,
                    "user" => null,
                    "errors" => ["User could not be created"]
                ]);
            }else{
                return $this->redirect("Home", "Index");
            }
        }
    }
}
