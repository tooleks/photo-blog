<template>
    <div class="loader" v-if="isVisible">
        <div class="loader-inner">
            <span class="loader-icon">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw" aria-hidden="true"></i>
            </span>
        </div>
    </div>
</template>

<style scoped>
    .loader {
        position: fixed;
        top: 45%;
        left: 45%;
        right: 45%;
        bottom: 45%;
        z-index: 1000;
    }

    .loader-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .loader-icon {
        background: rgba(0, 0, 0, 0.4);
        color: #dde3e6;
        padding: 1rem;
        border-radius: 0.5rem;
    }
</style>

<script>
    export default {
        props: {
            isLoading: {
                type: Boolean,
                default: true,
            },
            delay: {
                type: Number,
                default: 1000,
            },
        },
        data: function () {
            return {
                isVisible: this.isLoading,
                delayTimeout: null,
            };
        },
        watch: {
            isLoading: function () {
                this.init();
            },
        },
        methods: {
            init: function () {
                if (!this.isLoading) {
                    this.clearDelayTimeout();
                    this.hideLoader();
                    this.isVisible = this.isLoading;
                } else {
                    this.setDelayTimeout(() => this.showLoader());
                }
            },
            showLoader: function () {
                this.isVisible = true;
            },
            hideLoader: function () {
                this.isVisible = false;
            },
            clearDelayTimeout: function () {
                if (this.delayTimeout) {
                    clearTimeout(this.delayTimeout);
                    this.delayTimeout = null;
                }
            },
            setDelayTimeout: function (callback) {
                this.delayTimeout = setTimeout(() => callback.call(callback), this.delay);
            },
        },
    }
</script>
