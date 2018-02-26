<template>
    <transition name="fade">
        <div class="loader" v-if="isVisible">
            <div class="loader-inner">
            <span class="loader-icon">
                Loading...
            </span>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    .loader {
        position: fixed;
        top: 40%;
        left: 40%;
        right: 40%;
        bottom: 40%;
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
        padding: 0.76rem 1.5rem;
        border-radius: 0.25rem;
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
        beforeDestroy: function () {
            this.clearDelayTimeout();
        },
    }
</script>
