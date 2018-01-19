<template>
    <div>
        <router-link
                class="btn btn-secondary btn-sm btn-tag-link d-inline-block"
                v-for="tag in tags" :key="tag"
                :to="{name: 'photos-tag', params: {tag: tag}}"
                :title="`Find all tagged as ${tag}`">
            <i class="fa fa-tag" aria-hidden="true"></i> {{ tag }}
        </router-link>
    </div>
</template>

<style scoped>
    .btn-tag-link {
        margin-right: 2px;
        margin-bottom: 2px;
    }

    .btn-tag-link:last-child {
        margin-right: 0;
    }
</style>

<script>
    export default {
        props: {
            tags: {
                type: Array,
                default: () => {
                    return [];
                },
            },
        },
    }
</script>
