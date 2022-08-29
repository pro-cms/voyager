<template>
    <Card :title="__('voyager::generic.media')" icon="photo">
        <div class="mt-4">
            <MediaManager
                :upload-url="uploadUrl"
                :list-url="listUrl"
                :drag-text="dragText"
                :drop-text="dropText"
                :allowed-mime-types="allowedMimeTypes"
            >
            </MediaManager>
        </div>
    </Card>
</template>

<script>
export default {
    props: ['uploadUrl', 'listUrl', 'dragText', 'dropText', 'allowedMimeTypes'],
}
</script>