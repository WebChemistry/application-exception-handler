<?php declare(strict_types = 1);

namespace WebChemistry\ApplicationExceptionHandler;

use Nette\Application\IResponse;
use Nette\Application\Request;

trait ApplicationExceptionHandlerTrait
{

	public function run(Request $request): IResponse
	{
		$this->initializeApplicationExceptionHandlers($handler = new ApplicationExceptionHandler());

		return $handler->run(
			$this,
			fn () => parent::run($request),
			fn (...$args) => $this->createRequest($this, ...$args)
		);
	}

	abstract protected function initializeApplicationExceptionHandlers(ApplicationExceptionHandler $handler): void;

}
