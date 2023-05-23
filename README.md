1. [Requirements](#Requirements)
1. [Installation](#Installation)
1. [Usage](#Usage)
   1. [Conditional Field](#conditional-field)
   2. [ConditionalMany Field](#conditionalmany-field)

## Requirements

- `php: ^7.4 | ^8`
- `laravel/nova: ^4`

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require lupennat/conditionals
```

## Usage

### Conditional Field

Conditional Field can be used to switch a Field based on other fields values. It use native Nova `dependsOn` to intercept field changes, the conditions will be resolved in all pages (Index, Detail, Forms).

```php

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lupennat\Conditionals\Conditional;

class Item extends Resource
{

    public function fields(Request $request)
    {
        return [
            Select::make(__('Type'), 'type')->options([
                'boolean' => 'Boolean',
                'number' => 'Number',
                'not-exists' => 'No Value',
                'text' => 'Text'
            ]),
            Conditional::make(__('Value'))
                ->dependsOn(['type'], function (Conditional $field, NovaRequest $novaRequest, FormData $formData) {
                    return match ($formData->type) {
                        'boolean' => Boolean::make(__('Value'), 'value')->rules('required')
                        'number' => Number::make(__('Value'), 'value')->rules('required', 'min:10', 'max:100'),
                        'text' => Text::make(__('Value'), 'value')->rules('required'),
                        default =>  Hidden::make(__('Value'), 'value')->nullable()->fillUsing(function ($request, $model, $attribute) {
                            // always reset value to null
                            $model->{$attribute} = null;
                        })
                    }
                }),
        ];
    }
}
```

> If you do not want to store/change a value you can return null instead of a field.\
> When null will be returned an Unfillable Field will be used.


### Conditional Field

ConditionalMany Field can be used to Display Inline a single Field for any Many Relation.

```php

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Lupennat\Conditionals\ConditionalMany;

class ItemList extends Resource
{

    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),
            ConditionalMany::make('Item Value', 'items')
                ->each(
                    function ($item) {
                        return match ($item->type) {
                            'boolean' => Boolean::make(__('Value'), 'value')
                            'number' => Number::make(__('Value'), 'value'),
                            'text' => Text::make(__('Value'), 'value'),
                            default =>  Hidden::make(__('Value'), 'value')
                        }
                    }
                )
        ];
    }
}
```

> ConditionalMany Field can be used only on Index and Detail page.