<?php declare(strict_types = 1);

namespace WebChemistry\ApplicationExceptionHandler;

use Nette\Application\IResponse;
use Nette\Application\UI\Presenter;
use Throwable;
use WebChemistry\ApplicationExceptionHandler\ApplicationExceptionRequest;

final class ApplicationExceptionHandler
{

	/** @var callable[] */
	private array $catchExceptions = [];

	public function addCatchException(string $class, callable $callback): self
	{
		$this->catchExceptions[$class] = $callback;

		return $this;
	}

	public function addMultiCatchException(array $classes, callable $callback): self
	{
		foreach ($classes as $class) {
			$this->addCatchException($class, $callback);
		}

		return $this;
	}

	public function run(Presenter $presenter, callable $run, callable $createRequest): IResponse
	{
		try {
			return $run();
		} catch (Throwable $exception) {
			if (!($response = $this->processException($exception, $presenter, $createRequest))) {
				throw $exception;
			}

			return $response;
		}
	}

	private function processException(Throwable $exception, Presenter $presenter, callable $createRequest): ?IResponse
	{
		$request = new ApplicationExceptionRequest($presenter, $createRequest);

		foreach ($this->catchExceptions as $class => $callback) {
			if (is_a($exception, $class)) {
				$callback($request);

				return $request->getResponse();
			}
		}

		return null;
	}

}
