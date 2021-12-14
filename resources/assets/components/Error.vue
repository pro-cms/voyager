<template>
    <Card :title="__('voyager::generic.error', { code: exception.status })">
        <h4>{{ exception.message }}</h4>
        <p class="mb-4">
            <code>{{ exception.file }}</code> at line <code>{{ exception.line }}</code>
        </p>

        <template v-for="(line, i) in exception.trace" :key="`trace-${i}`">
            <Collapsible :title-size="6" :title="`${line.file} @ line ${line.line}`" closed>
                <p>{{ line.class }}::{{ line.function }}</p>
                <pre>{{ JSON.stringify(line.args, null, 4) }}</pre>
            </Collapsible>
        </template>
    </Card>
</template>
<script>
export default {
    props: {
        exception: Object
    },
    /*
     * exception.exception can be one of:
     * - Voyager\Admin\Exceptions\NoLayoutFoundException
     *      => No layout was found for the given BREAD and action
     * - \Voyager\Admin\Exceptions\TableNotFoundException
     *      => The table used for editing a BREAD was not found
     */
}
</script>