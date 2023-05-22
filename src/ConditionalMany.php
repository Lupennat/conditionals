<?php

namespace Lupennat\Conditionals;

use Illuminate\Support\Collection;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class ConditionalMany extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'conditional-many';

    /**
     * Indicates if the element should be shown on the creation view.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest):(bool))|bool
     */
    public $showOnCreation = false;

    /**
     * Indicates if the element should be shown on the update view.
     *
     * @var (callable(\Laravel\Nova\Http\Requests\NovaRequest, mixed):(bool))|bool
     */
    public $showOnUpdate = false;

    /**
     * The callback to be used to resolve the related fields.
     *
     * @var (callable(mixed):(Field))|null
     */
    public $eachCallback;

    /**
     * The Fields map.
     *
     * @var array<string,Field>
     */
    protected $fields = [];

    /**
     * Each Element Relations.
     *
     * @return $this
     */
    public function each(callable $eachCallback)
    {
        $this->eachCallback = $eachCallback;

        return $this;
    }

    /**
     * Resolve the field's value for display.
     *
     * @param mixed       $resource
     * @param string|null $attribute
     *
     * @return void
     */
    public function resolveForDisplay($resource, $attribute = null)
    {
        parent::resolveForDisplay($resource, $attribute);

        $this->resolveFields($this->value);

        $this->value = null;
    }

    /**
     * Resolve Related Fields.
     *
     * @param mixed $items
     *
     * @return void;
     */
    public function resolveFields($items)
    {
        if ($items instanceof Collection && is_callable($this->eachCallback)) {
            $this->fields = $items->map(function ($item) {
                $field = call_user_func($this->eachCallback, $item);
                $field->resolveForDisplay($item);

                return $field;
            })->values();
        }

        $this->withMeta(['fields' => $this->fields]);
    }

    /**
     * Check for showing when updating.
     *
     * @param mixed $resource
     */
    public function isShownOnUpdate(NovaRequest $request, $resource): bool
    {
        return false;
    }

    /**
     * Check for showing when creating.
     */
    public function isShownOnCreation(NovaRequest $request): bool
    {
        return false;
    }
}
