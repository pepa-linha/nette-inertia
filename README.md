# Nette Inertia

The [Nette](https://nette.org) adapter for [Inertia.js](https://inertiajs.com/).

## Installation

```
composer require pepa-linha/nette-inertia
```

## Usage

Extends your presenter

```
class BasePresenter extends InertiaPresenter
```

and then use `$this->inertia(...)` method to send page object.
