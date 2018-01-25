<template>
    <textarea v-model.trim="input"
              v-bind="attributes"></textarea>
</template>

<script>
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
                default: () => {
                    return [];
                },
            },
        },
        data: function () {
            return {
                input: "",
            };
        },
        watch: {
            tags: function (tags) {
                const value = tags.join(",");
                if (value !== this.input) {
                    this.input = value;
                }
            },
            input: function (input) {
                this.input = input.split(" ").join("_").toLowerCase();
                this.$emit("update:tags", this.input.split(","));
            },
        },
    }
</script>
