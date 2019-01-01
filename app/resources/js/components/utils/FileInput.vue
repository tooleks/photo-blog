<template>
    <label class="file-input card bg-light"
           v-bind="$attrs"
           v-on-drop-file="onSelect">
        <slot>
            <span>
                <i class="fa fa-cloud-upload" aria-hidden="true"></i> {{ $lang("Choose a file or drag it here.") }}
            </span>
        </slot>
        <input v-if="ready"
               type="file"
               hidden
               v-bind="$attrs"
               @change="onSelect($event.target.files.item(0))">
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
        inheritAttrs: false,
        data() {
            return {
                /** @type {boolean} */
                ready: true,
            };
        },
        methods: {
            onSelect(file) {
                this.$emit("select", file);
                this.reset();
            },
            reset() {
                this.ready = false;
                this.$nextTick(() => {
                    this.ready = true;
                });
            },
        },
    }
</script>
