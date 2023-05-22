<template>
    <component
        :is="componentName"
        :class="`text-${field.conditionalField.textAlign}`"
        :field="field.conditionalField"
        :resource="resource"
        :resource-name="resourceName"
        :via-resource="viaResource"
        :via-resource-id="viaResourceId"
    />
</template>

<script>
    export default {
        props: ['resourceName', 'field', 'viaResource', 'viaResourceId', 'resource'],
        computed: {
            componentName() {
                return this.field.conditionalField.prefixComponent
                    ? 'index-' + this.field.conditionalField.component
                    : this.field.conditionalField.component;
            }
        }
    };
</script>
