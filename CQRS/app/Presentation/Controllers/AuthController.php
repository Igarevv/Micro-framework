<?php

namespace App\Presentation\Controllers;

use App\Application\Presenter\UserPresenter;
use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\Application\UseCase\User\CreateUser\CreateUserHandler;
use App\Application\UseCase\User\LoginUser\LoginCommand;
use App\Application\UseCase\User\LoginUser\LoginCommandHandler;
use App\Domain\Based\Exception\InvalidFormat;
use App\Domain\User\Exception\AuthException;
use App\Infrastructure\Bus\Command\CommandBusInterface;
use App\Infrastructure\Services\Session\FlashMessageHandler;
use Igarevv\Micrame\Controller\Controller;
use Igarevv\Micrame\Http\Response\RedirectResponse;
use Igarevv\Micrame\Http\Response\Response;
use Igarevv\Micrame\Session\AuthSession;
use Igarevv\Micrame\Session\Session;

class AuthController extends Controller
{

    public function __construct(
      private readonly CommandBusInterface $commandBus,
      private readonly AuthSession $auth,
      private readonly FlashMessageHandler $flasher
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
        $inputData = $this->request->getPost();

        $command = new CreateUserCommand($inputData);

        try {
            $this->commandBus->dispatch($command, CreateUserHandler::class);
        } catch (AuthException|InvalidFormat $e) {
            $this->flasher->setError('error', $e->getMessage(), [
              'firstName' => $command->firstName(),
              'lastName' => $command->lastName(),
              'email' => $command->email(),
            ]);

            return new RedirectResponse('/sign-up');
        }
        $this->flasher->setSuccess('success-register',
          'Registration completed successfully. Login now!', [
            'name' => "{$command->firstName()} {$command->lastName()}",
          ]);

        return new RedirectResponse('/');
    }

    public function login(): Response
    {
        $inputData = $this->request->getPost(['email', 'password']);

        $command = new LoginCommand($inputData['email'],
          $inputData['password']);

        try {
            $user = $this->commandBus->dispatch($command,
              LoginCommandHandler::class);
        } catch (AuthException $e) {
            $this->flasher->setError('error', $e->getMessage());
            return new RedirectResponse('/sign-in');
        }

        $this->request->session()->regenerate();
        $this->request->session()->set(Session::AUTH, [
          UserPresenter::USER_ID => $user->id,
          UserPresenter::FIRST_NAME => $user->firstName,
          UserPresenter::LAST_NAME => $user->lastName,
        ]);

        return new RedirectResponse('/');
    }

    public function logout(): Response
    {
        $this->auth->logout();

        return new RedirectResponse('/');
    }

}