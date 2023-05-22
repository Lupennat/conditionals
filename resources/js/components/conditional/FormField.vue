<template>
    <component
        :index="index"
        :key="componentKey"
        :is="componentName"
        :errors="errors"
        :resource-id="resourceId"
        :resource-name="resourceName"
        :related-resource-name="relatedResourceName"
        :related-resource-id="relatedResourceId"
        :field="currentField.conditionalField"
        :via-resource="viaResource"
        :via-resource-id="viaResourceId"
        :via-relationship="viaRelationship"
        :shown-via-new-relation-modal="shownViaNewRelationModal"
        :form-unique-id="formUniqueId"
        :mode="mode"
        :show-help-text="true"
        @field-shown="$emit('field-shown')"
        @field-hidden="$emit('field-hidden')"
        @field-changed="$emit('field-changed')"
        @file-deleted="$emit('update-last-retrieved-at-timestamp')"
        @file-upload-started="$emit('file-upload-started')"
        @file-upload-finished="$emit('file-upload-finished')"
    />
</template>

<script>
    import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';

    export default {
        mixins: [DependentFormField, HandlesValidationErrors],

        data() {
            return {
                updateKey: 0
            };
        },

        props: [
            'resourceName',
            'index',
            'relatedResourceName',
            'viaResource',
            'showHelpText',
            'viaResourceId',
            'mode',
            'viaRelationship',
            'shownViaNewRelationModal',
            'relatedResourceId',
            'resourceId',
            'field',
            'panel'
        ],
        computed: {
            componentName() {
                return this.currentField.conditionalField.prefixComponent
                    ? 'form-' + this.currentField.conditionalField.component
                    : this.currentField.conditionalField.component;
            },
            componentKey() {
                return `${this.currentField.uniqueCondition}-${this.index}-${this.updateKey}`;
            }
        },
        methods: {
            /**
             * Fill the given FormData object with the field's internal value.
             */
            fill(formData) {
                try {
                    if (!this.currentField.conditionalField.unfillable) {
                        this.currentField.conditionalField.fill(formData);
                    }
                } catch (error) {
                    console.log(error);
                    throw error;
                }
            },
            onSyncedField() {
                this.updateKey++;
            }
        }
    };
</script>
