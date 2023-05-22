<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <div class="divide-y divide-gray-100">
                <div v-for="(child, indexChild) in field.fields" :key="indexChild">
                    <component
                        :key="indexChild"
                        :index="indexChild"
                        :is="getChildComponentName(child)"
                        :resource-name="resourceName"
                        :resource-id="resourceId"
                        :resource="resource"
                        :field="child"
                        @actionExecuted="$emit('actionExecuted')"
                    />
                </div>
            </div>
        </template>
    </PanelItem>
</template>

<script>
    export default {
        props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
        methods: {
            getChildComponentName(field) {
                return field.prefixComponent ? 'detail-' + field.component : field.component;
            }
        }
    };
</script>
