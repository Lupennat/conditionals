<?php

namespace Lupennat\Conditionals;

use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Unfillable as FieldsUnfillable;

class Unfillable extends Hidden implements FieldsUnfillable
{
    /**
     * Prepare the field for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'unfillable' => true,
        ]);
    }
}
