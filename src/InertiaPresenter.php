<?php

declare(strict_types=1);

namespace PL\NetteInertia;

use Nette\Application\Responses\VoidResponse;
use Nette\Application\UI\Presenter;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Utils\Json;

abstract class InertiaPresenter extends Presenter
{
    private ?PageObject $pageObject = null;

    protected function startup()
    {
        parent::startup();

        if ($this->isInertiaRequest()) {
            if ($this->getInertiaAssetVersion() !== $this->getAssetVersion()
                && $this->getHttpRequest()->isMethod(IRequest::GET))
            {
                $httpResponse = $this->getHttpResponse();
                $httpResponse->addHeader('X-Inertia-Location', $this->link('this'));
                $httpResponse->setCode(IResponse::S409_CONFLICT);
                $response = new VoidResponse();
                $response->send($this->getHttpRequest(), $httpResponse);
                $this->sendResponse($response);
            }

            $this->addInertiaHeaders();
        }
    }

    protected function afterRender()
    {
        parent::afterRender();
        $this->template->inertiaPageObject = Json::encode($this->pageObject->getData());
    }

    public function inertia(array $props = [], ?string $component = null, ?string $link = null)
    {
        $pageObject = new PageObject(
            $component ?? $this->getInertiaComponentName(),
            $props,
            $link ?? $this->link('this'),
            $this->getAssetVersion()
        );

        if ($this->isInertiaRequest()) {
            $this->sendJson($pageObject->getData());
        }

        $this->pageObject = $pageObject;
    }

    public function isInertiaRequest(): bool
    {
        return $this->getHttpRequest()->getHeader('X-Inertia') === 'true';
    }

    public function isInertiaPartialReload(): bool
    {
        return $this->getHttpRequest()->getHeader('X-Inertia-Partial-Component') !== null
            && $this->getHttpRequest()->getHeader('X-Inertia-Partial-Data') !== null;
    }

    public function getInertiaPartialDesiredProps(): array
    {
        $data = $this->getHttpRequest()->getHeader('X-Inertia-Partial-Data');
        return $data !== null ? explode(',', $data) : [];
    }

    protected function getInertiaComponentName(): string
    {
        return $this->getName();
    }

    private function addInertiaHeaders(): void
    {
        $response = $this->getHttpResponse();
        $response->addHeader('Vary', 'Accept');
        $response->addHeader('X-Inertia', 'true');
    }

    private function getInertiaAssetVersion(): ?string
    {
        return $this->getHttpRequest()->getHeader('X-Inertia-Version');
    }

    abstract protected function getAssetVersion(): string;
}
