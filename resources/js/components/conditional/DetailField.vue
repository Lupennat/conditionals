<template>
    <component
        :key="index"
        :index="index"
        :is="componentName"
        :resource-name="resourceName"
        :resource-id="resourceId"
        :resource="resource"
        :field="field.conditionalField"
        @actionExecuted="$emit('actionExecuted')"
    />
</template>

<script>
    export default {
        props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
        computed: {
            componentName() {
                return this.field.conditionalField.prefixComponent
                    ? 'detail-' + this.field.conditionalField.component
                    : this.field.conditionalField.component;
            }
        }
    };
</script>
