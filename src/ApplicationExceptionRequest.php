<?php declare(strict_types = 1);

namespace WebChemistry\ApplicationExceptionHandler;

use Nette\Application\IResponse;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\RedirectResponse;
use Nette\Application\UI\Presenter;
use Nette\Http\IResponse as IHttpResponse;

final class ApplicationExceptionRequest
{

	private ?IResponse $response = null;

	/** @var callable */
	private $createRequest;

	public function __construct(
		private Presenter $presenter,
		callable $createRequest
	)
	{
		$this->createRequest = $createRequest;
	}

	public function flashMessage(string $message, string $type = 'success'): void
	{
		$this->presenter->flashMessage($message, $type);
	}

	public function link(string $destination, array $args = []): string
	{
		return $this->presenter->link($destination, $args);
	}

	public function redirect(string $destination, array $args = []): void
	{
		$this->redirectUrl(($this->createRequest)($destination, $args, 'redirect'));
	}

	public function redirectUrl(string $url, int $httpCode = null): void
	{
		if ($this->presenter->isAjax()) {
			$this->response = new JsonResponse([
				'redirect' => $url,
			]);

			return;
		} elseif (!$httpCode) {
			$isPost = $this->presenter->getHttpRequest()->isMethod('post');
			$httpCode = $isPost ? IHttpResponse::S303_POST_GET : IHttpResponse::S302_FOUND;
		}

		$this->response = new RedirectResponse($url, $httpCode);
	}

	public function getResponse(): ?IResponse
	{
		return $this->response;
	}

}
