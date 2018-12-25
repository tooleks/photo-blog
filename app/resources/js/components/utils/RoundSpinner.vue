<template>
    <transition name="fade">
        <div class="round-spinner" v-if="visible">
            <div class="round-spinner-inner">
            <span class="round-spinner-icon" :aria-label="$lang('Loading...')">
                <div class="round-spinner-animation" aria-hidden="true"></div>
            </span>
            </div>
        </div>
    </transition>
</template>

<style scoped>
    .round-spinner {
        position: fixed;
        top: 40%;
        left: 40%;
        right: 40%;
        bottom: 40%;
        z-index: 1000;
    }

    .round-spinner-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .round-spinner-icon {
        background: rgba(0, 0, 0, 0.4);
        color: #dde3e6;
        padding: 1em;
        border-radius: 50%;
    }

    .round-spinner-animation,
    .round-spinner-animation:after {
        border-radius: 50%;
        width: 10em;
        height: 10em;
    }

    .round-spinner-animation {
        font-size: 4px;
        position: relative;
        text-indent: -9999em;
        border-top: 1.1em solid rgba(255, 255, 255, 0.2);
        border-right: 1.1em solid rgba(255, 255, 255, 0.2);
        border-bottom: 1.1em solid rgba(255, 255, 255, 0.2);
        border-left: 1.1em solid #ffffff;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 1.1s infinite linear;
        animation: load8 1.1s infinite linear;
    }

    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
</style>

<script>
    export default {
        props: {
            loading: {
                type: Boolean,
                default: true,
            },
            delay: {
                type: Number,
                default: 1000,
            },
        },
        data() {
            return {
                /** @type {boolean} */
                visible: this.loading,
                /** @type {Function|null} */
                delayTimeout: null,
            };
        },
        watch: {
            loading() {
                this.initSpinner();
            },
        },
        methods: {
            initSpinner() {
                if (!this.loading) {
                    this.clearDelayTimeout();
                    this.hideSpinner();
                } else {
                    this.setDelayTimeout(() => this.showSpinner());
                }
            },
            showSpinner() {
                this.visible = true;
            },
            hideSpinner() {
                this.visible = false;
            },
            clearDelayTimeout() {
                if (this.delayTimeout) {
                    clearTimeout(this.delayTimeout);
                    this.delayTimeout = null;
                }
            },
            setDelayTimeout(callback) {
                this.delayTimeout = setTimeout(() => callback.call(callback), this.delay);
            },
        },
        beforeDestroy() {
            this.clearDelayTimeout();
        },
    }
</script>
