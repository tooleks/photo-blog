<template>
    <label :id="id"
           :class="attributes.class"
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
            id: {
                type: String,
                default: () => {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `file-input-${id}`;
                },
            },
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
        computed: {
            fileInput: function () {
                return document.getElementById(this.id);
            },
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
            preventDefaultEvents: function (event) {
                event.preventDefault();
                event.stopPropagation();
            },
        },
        mounted: function () {
            this.fileInput.addEventListener("drag", this.preventDefaultEvents);
            this.fileInput.addEventListener("dragstart", this.preventDefaultEvents);
            this.fileInput.addEventListener("dragend", this.preventDefaultEvents);
            this.fileInput.addEventListener("dragover", this.preventDefaultEvents);
            this.fileInput.addEventListener("dragenter", this.preventDefaultEvents);
            this.fileInput.addEventListener("dragleave", this.preventDefaultEvents);
            this.fileInput.addEventListener("drop", this.preventDefaultEvents);
            this.fileInput.addEventListener("drop", (event) => {
                const file = event.dataTransfer.files.item(0);
                this.onFileChange(file);
            });
        },
        beforeDestroy: function () {
            this.fileInput.removeEventListener("drag", this.preventDefaultEvents);
            this.fileInput.removeEventListener("dragstart", this.preventDefaultEvents);
            this.fileInput.removeEventListener("dragend", this.preventDefaultEvents);
            this.fileInput.removeEventListener("dragover", this.preventDefaultEvents);
            this.fileInput.removeEventListener("dragenter", this.preventDefaultEvents);
            this.fileInput.removeEventListener("dragleave", this.preventDefaultEvents);
            this.fileInput.removeEventListener("drop", this.preventDefaultEvents);
        },
    }
</script>
