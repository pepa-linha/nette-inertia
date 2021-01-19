<?php

declare(strict_types=1);

namespace PL\NetteInertia;

class PageObject
{
    private string $component;

    private array $props;

    private string $url;

    private string $version;

    public function __construct(
        string $component,
        array $props,
        string $url,
        string $version
    ) {
        $this->component = $component;
        $this->props = $props;
        $this->url = $url;
        $this->version = $version;
    }

    public function getData(): array
    {
        return [
            'component' => $this->component,
            'props' => $this->props,
            'url' => $this->url,
            'version' => $this->version,
        ];
    }
}
