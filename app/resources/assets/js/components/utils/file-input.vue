<template>
    <label :class="attributes.class"
           :disabled="attributes.disabled">
        <slot>
            Upload file
        </slot>
        <input v-if="fileInputReady"
               hidden
               type="file"
               v-bind="attributes"
               @change="onFileChange($event.target.files.item(0))">
    </label>
</template>

<style scoped>
    label {
        margin-bottom: initial;
    }

    [disabled] {
        cursor: not-allowed !important;
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
        data: function () {
            return {
                fileInputReady: true,
            };
        },
        methods: {
            onFileChange: function (file) {
                this.$emit("change", file);
                this.clearFileInput();
            },
            clearFileInput: function () {
                this.fileInputReady = false;
                this.$nextTick(() => {
                    this.fileInputReady = true;
                });
            },
        },
    }
</script>
