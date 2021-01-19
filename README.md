# Nette Inertia

The [Nette](https://nette.org) adapter for [Inertia.js](https://inertiajs.com/).

## Installation

```
composer require pepa-linha/nette-inertia
```

## Usage

Extends your presenter

```php
class BasePresenter extends InertiaPresenter
```

and then use `$this->inertia(...)` method in your presenters to send page object response.

By default Inertia page component name is the same like presenter name. You can change it by override
`getInertiaComponentName` method.

Set the default state of the Inertia application with `$inertiaPageObject` in template:

```latte
<div id="app" data-page='{$inertiaPageObject|noescape}'></div>
```

