<template>
    <label :class="attributes.class"
           :disabled="attributes.disabled">
        <slot>
            Upload file
        </slot>
        <input hidden
               type="file"
               v-bind="attributes"
               @change="onFileChange($event.target.files.item(0))">
    </label>
</template>

<style scoped>
    label {
        margin-bottom: initial;
    }

    [hidden] {
        display: none !important;
    }
</style>

<script>
    export default {
        props: {
            attributes: {
                type: Object,
                default: () => {
                    return {
                        class: "btn btn-secondary",
                    };
                },
            },
        },
        methods: {
            onFileChange: function (file) {
                this.$emit("change", file);
            },
        },
    }
</script>
