<template>
    <label class="file-input card bg-light"
           :class="attributes.class"
           :disabled="attributes.disabled"
           v-on-drop-file="onFileChange"
           title="Choose a file or drag it here.">
        <slot>
            <span>
                <i class="fa fa-cloud-upload" aria-hidden="true"></i> Choose a file or drag it here.
            </span>
        </slot>
        <input v-if="fileInputReady"
               hidden
               type="file"
               v-bind="attributes"
               @change="onFileChange($event.target.files.item(0))">
    </label>
</template>

<style lang="scss" scoped>
    .file-input {
        display: flex;
        justify-content: center;
        align-content: center;
        height: 5em;
        margin: 0;
        padding: 0;
        text-align: center;
        cursor: pointer;

        &[disabled] {
            cursor: not-allowed !important;
        }
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
        data() {
            return {
                /** @type {boolean} */
                fileInputReady: true,
            };
        },
        methods: {
            onFileChange(file) {
                this.$emit("change", file);
                this.clearFileInput();
            },
            clearFileInput() {
                this.fileInputReady = false;
                this.$nextTick(() => {
                    this.fileInputReady = true;
                });
            },
        },
    }
</script>
