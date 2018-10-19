<template>
    <textarea v-model.trim="input" v-bind="attributes"></textarea>
</template>

<script>
    import Tag from "../../entities/Tag";

    export default {
        props: {
            attributes: {
                type: Object,
                default() {
                    return {};
                },
            },
            tags: {
                type: Array,
                default() {
                    return [];
                },
                validator(tags) {
                    return Array.isArray(tags) && tags.every((tag) => tag instanceof Tag);
                },
            },
        },
        data() {
            return {
                /** @type {string} */
                input: "",
            };
        },
        watch: {
            input() {
                this.syncInput();
            },
            tags() {
                this.syncTags();
            },
        },
        methods: {
            syncInput() {
                this.input = this.input.split(" ").join("_").toLowerCase();
                const tags = this.input.split(",").map((value) => Tag.fromValue(value));
                this.$emit("update:tags", tags);
            },
            syncTags() {
                const value = this.tags.map((tag) => String(tag)).join(",");
                if (value !== this.input) {
                    this.input = value;
                }
            },
        },
        created() {
            this.syncTags();
        },
    }
</script>
