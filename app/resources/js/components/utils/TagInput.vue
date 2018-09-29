<template>
    <textarea v-model.trim="input" v-bind="attributes"></textarea>
</template>

<script>
    import Tag from "../../entities/Tag";

    export default {
        props: {
            attributes: {
                type: Object,
                default: () => {
                    return {};
                },
            },
            tags: {
                type: Array,
                default: [],
                validator: function (tags) {
                    return Array.isArray(tags) && tags.every((tag) => tag instanceof Tag);
                },
            },
        },
        data: function () {
            return {
                input: "",
            };
        },
        watch: {
            input: function () {
                this.syncInput();
            },
            tags: function () {
                this.syncTags();
            },
        },
        methods: {
            syncInput: function () {
                this.input = this.input.split(" ").join("_").toLowerCase();
                const tags = this.input.split(",").map((value) => Tag.fromValue(value));
                this.$emit("update:tags", tags);
            },
            syncTags: function () {
                const value = this.tags.map((tag) => String(tag)).join(",");
                if (value !== this.input) {
                    this.input = value;
                }
            },
        },
        created: function () {
            this.syncTags();
        },
    }
</script>
