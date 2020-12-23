## Application exception handler

```php

use Nette\Application\UI\Presenter;
use WebChemistry\ApplicationExceptionHandler\ApplicationExceptionHandler;
use WebChemistry\ApplicationExceptionHandler\ApplicationExceptionHandlerTrait;
use WebChemistry\ApplicationExceptionHandler\ApplicationExceptionRequest;

abstract class BasePresenter extends Presenter {

	use ApplicationExceptionHandlerTrait;

	protected function initializeApplicationExceptionHandlers(ApplicationExceptionHandler $handler): void
	{
		$handler->addCatchException(
			[UserNotLoggedInException::class],
			function (ApplicationExceptionRequest $request): void {
				$request->flashMessage('Musíte se nejprve přihlásit.', 'error');

				$request->redirect('@signIn', [
					'backlink' => $request->link('this', ['backlink' => null]),
				]);
			}
		);
	}

}

```
