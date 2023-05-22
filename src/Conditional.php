<?php

namespace Lupennat\Conditionals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\MorphTo;
use Laravel\Nova\Fields\SupportsDependentFields;
use Laravel\Nova\Http\Requests\NovaRequest;

class Conditional extends Field
{
    use SupportsDependentFields { dependsOn as novaDependsOn; }

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'conditional';

    /**
     * Conditional Field.
     *
     * @var Field
     */
    public $conditionalField;

    /**
     * Switch Callback.
     *
     * @var (callable(Fluent):(Field|null))
     */
    protected $switchCallback;

    /**
     * Conditional Attributes.
     *
     * @var array
     */
    protected $conditionalAttributes = [];

    /**
     * Create a new field.
     *
     * @param string                               $name
     * @param string|\Closure|callable|object|null $attribute
     *
     * @return void
     */
    public function __construct($name, $attribute = null)
    {
        parent::__construct($name, $attribute, null);

        $this->conditionalField = Unfillable::make($name, $attribute);
    }

    /**
     * Register depends on to a field.
     *
     * @param string|\Laravel\Nova\Fields\Field|array<int, string|\Laravel\Nova\Fields\Field> $attributes
     * @param  (callable(Fluent):(Field|null))
     *
     * @return $this
     */
    public function dependsOn($attributes, callable $callable)
    {
        $this->conditionalAttributes = collect(Arr::wrap($attributes))->map(function ($item) {
            if ($item instanceof MorphTo) {
                return [$item->attribute, "{$item->attribute}_type"];
            }

            return $item instanceof Field ? $item->attribute : $item;
        })->flatten()->all();

        $this->switchCallback = $callable;

        $this->novaDependsOn($this->conditionalAttributes, function (Conditional $field, NovaRequest $novaRequest, FormData $formData) {
            // dump('dependsCallback');

            $this->resolveForDepends($novaRequest, $this->resource, $formData);
        });

        // $this->setConditionalField(app(NovaRequest::class), new FormData(array_fill_keys($this->conditionalAttributes, null), app(NovaRequest::class)));

        return $this;
    }

    /**
     * Change Field To display.
     */
    protected function setConditionalField(NovaRequest $request, FormData $attributes)
    {
        $this->conditionalField = call_user_func($this->switchCallback, $this, $request, $attributes) ?? Unfillable::make($this->name);
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
        // dump('resolveForDisplay');
        $this->resource = $resource;

        $this->setConditionalFieldFromResource($resource);

        $this->conditionalField->resolveForDisplay($resource, $attribute);

        $this->value = $this->conditionalField->value;
        $this->usesCustomizedDisplay = $this->conditionalField->usesCustomizedDisplay;
        $this->displayedAs = $this->conditionalField->displayedAs;
    }

    /**
     * Resolve the field's value.
     *
     * @param mixed       $resource
     * @param string|null $attribute
     *
     * @return void
     */
    public function resolve($resource, $attribute = null)
    {
        $this->resource = $resource;

        $this->setConditionalFieldFromResource($resource);

        $this->conditionalField->resolve($resource, $attribute);

        $this->value = $this->conditionalField->value;
    }

    /**
     * Resolve the field's value for depends.
     *
     * @param mixed     $resource
     * @param Formadata $formdata
     *
     * @return void
     */
    protected function resolveForDepends(NovaRequest $request, $resource, $formData)
    {
        // dump('resolveForDepends');
        $this->setConditionalFieldFromFormdata($request, $formData);

        $cloned = clone $resource;

        foreach ($formData->toArray() as $key => $value) {
            if (str_starts_with($key, 'resource:')) {
                continue;
            }
            $cloned->$key = $value;
        }

        $this->conditionalField->resolve($cloned, null);

        $this->value = $this->conditionalField->value;
    }

    /**
     * Resolve dependent field value.
     *
     * @return mixed
     */
    public function resolveDependentValue(NovaRequest $request)
    {
        // dump('resolveDependentValue');

        return $this->conditionalField->resolveDependentValue($request);
    }

    /**
     * Resolve the default value for an Action field.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return void
     */
    public function resolveForAction($request)
    {
        // dump('resolveForAction');

        $this->setConditionalFieldFromRequest($request);

        $this->conditionalField->resolveForAction($request);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param object $model
     *
     * @return mixed
     */
    public function fill(NovaRequest $request, $model)
    {
        return $this->conditionalField->fill($request, $model);
    }

   /**
    * Hydrate the given attribute on the model based on the incoming request.
    *
    * @param  object  $model
    * @param  string  $attribute
    * @param  string|null  $requestAttribute
    *
    * @return mixed
    */
   public function fillInto(NovaRequest $request, $model, $attribute, $requestAttribute = null)
   {
       return $this->conditionalField->fillInto($request, $requestAttribute ?? $this->conditionalField->attribute, $model, $attribute);
   }

    /**
     * Get the validation rules for this field.
     *
     * @return array<string, TValidationRules>
     */
    public function getRules(NovaRequest $request)
    {
        return $this->conditionalField->getRules($request);
    }

    /**
     * Get the creation rules for this field.
     *
     * @return array<string, TValidationRules>
     */
    public function getCreationRules(NovaRequest $request)
    {
        return $this->conditionalField->getCreationRules($request);
    }

    /**
     * Get the update rules for this field.
     *
     * @return array<string, TValidationRules>
     */
    public function getUpdateRules(NovaRequest $request)
    {
        return $this->conditionalField->getUpdateRules($request);
    }

    /**
     * Get the validation attribute for the field.
     *
     * @return string
     */
    public function getValidationAttribute(NovaRequest $request)
    {
        return $this->conditionalField->getValidationAttribute($request);
    }

    /**
     * Get the validation attribute names for the field.
     *
     * @return array<string, string>
     */
    public function getValidationAttributeNames(NovaRequest $request)
    {
        return $this->conditionalField->getValidationAttributeNames($request);
    }

    /**
     * Determine if the field is readonly.
     *
     * @return bool
     */
    public function isReadonly(NovaRequest $request)
    {
        return $this->conditionalField->isReadonly($request);
    }

    /**
     * Determine if the field is required.
     *
     * @return bool
     */
    public function isRequired(NovaRequest $request)
    {
        return $this->conditionalField->isRequired($request);
    }

    /**
     * Return the validation key for the field.
     *
     * @return string
     */
    public function validationKey()
    {
        return $this->conditionalField->validationKey();
    }

    /**
     * set conditional field from request.
     *
     * @return void
     */
    protected function setConditionalFieldFromRequest(NovaRequest $request)
    {
        $this->setConditionalField($request, FormData::onlyFrom($request, $this->conditionalAttributes));
    }

    /**
     * set conditional field from resource.
     *
     * @return void
     */
    protected function setConditionalFieldFromResource($resource)
    {
        $data = [];
        foreach ($this->conditionalAttributes as $attribute) {
            $value = $resource->{$attribute};
            if ($value instanceof Model) {
                $value = $resource->getRelation($attribute)->getKey();
            }
            $data[$attribute] = $value;
        }

        $this->setConditionalField(app(NovaRequest::class), new FormData($data, app(NovaRequest::class)));
    }

    /**
     * set conditional field from resource.
     *
     * @return void
     */
    protected function setConditionalFieldFromFormData(NovaRequest $request, FormData $formData)
    {
        $this->setConditionalField($request, $formData);
    }

    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $request = app(NovaRequest::class);

        return array_merge(parent::jsonSerialize(), [
            'uniqueCondition' => md5(json_encode($this->getDependentsAttributes($request))),
            'conditionalField' => $this->conditionalField->jsonSerialize(),
        ]);
    }
}
