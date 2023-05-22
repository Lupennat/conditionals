<template>
    <div class="divide-y divide-gray-100 space-y-2">
        <div
            :class="`flex items-center text-${field.textAlign}${indexChild > 0 ? ' pt-2' : ''}`"
            v-for="(child, indexChild) in field.fields"
            :key="indexChild"
        >
            <div class="mr-2 font-bold">{{ child.name }}</div>
            <component
                :is="getChildComponentName(child)"
                :field="child"
                :resource="resource"
                :resource-name="resourceName"
                :via-resource="viaResource"
                :via-resource-id="viaResourceId"
            />
        </div>
    </div>
</template>
<script>
    export default {
        props: ['resourceName', 'field', 'viaResource', 'viaResourceId', 'resource'],
        methods: {
            getChildComponentName(field) {
                return field.prefixComponent ? 'index-' + field.component : field.component;
            }
        }
    };
</script>
