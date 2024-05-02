<?php

namespace App\Http\Controller;

use App\Exceptions\UserException;
use App\Http\Forms\RegisterForm;
use App\Services\AuthService;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\Session;

class LoginController extends Controller
{

    public function __construct(
      private AuthService $service,
      private AuthSession $auth
    ) {}

    public function signUpIndex(): Response
    {
        return $this->render('register.html.twig');
    }

    public function signInIndex(): Response
    {
        return $this->render('login.html.twig');
    }

    public function register(): Response
    {
        $form = new RegisterForm($this->request->getPost([
          'firstName', 'lastName', 'password', 'passwordConfirm', 'email',
        ]));

        $errors = $form->errors();

        if ($errors) {
            $this->request->session()->setFlash('error', [
              'errors' => $errors,
              'firstName' => $form->getInputFirstName(),
              'lastName'  => $form->getInputLastName(),
              'email'     => $form->getInputEmail(),
            ]);

            return new RedirectResponse('/sign-up');
        }

        $user = $form->getUserDto();

        try {
            $this->service->saveToDb($user);
        } catch (UserException $e) {
            $this->request->session()->setFlash('error', [
              'errors'    => $e->getMessage(),
              'firstName' => $form->getInputFirstName(),
              'lastName'  => $form->getInputLastName(),
            ]);

            return new RedirectResponse('/sign-up');
        }

        $this->request->session()->setFlash('success-register', [
          'name'    => $user->fullName(),
          'message' => 'Registration completed successfully. Login now!',
        ]);

        return new RedirectResponse('/');
    }

    public function login(): Response
    {
        $userData = $this->request->getPost(['email', 'password']);

        $isAuth = $this->service->authenticate($userData);

        if ($isAuth){
            $this->request->session()->regenerate();

            $this->request->session()->set(Session::AUTH, $this->service->getUser());

            return new RedirectResponse('/');
        }

        $this->request->session()->setFlash('error', 'Login failed. Please check email or password!');

        return new RedirectResponse('/sign-in');
    }

    public function logout(): Response
    {
        $this->auth->logout();

        return new RedirectResponse('/');
    }

}
