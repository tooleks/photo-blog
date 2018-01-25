<template>
    <div id="g-recaptcha" class="g-recaptcha" :data-sitekey="siteKey"></div>
</template>

<script>
    import config from "../../config";

    export default {
        props: {
            siteKey: {
                type: String,
                default: config.credentials.googleReCaptcha.siteKey,
            },
        },
        data: function () {
            return {
                widgetId: 0,
            };
        },
        methods: {
            isEnabled: function () {
                return window.grecaptcha && this.siteKey;
            },
            execute: function () {
                if (this.isEnabled) {
                    window.grecaptcha.execute(this.widgetId);
                } else {
                    this.emitVerifiedEvent();
                }
            },
            render: function () {
                if (this.isEnabled) {
                    this.widgetId = window.grecaptcha.render("g-recaptcha", {
                        sitekey: this.siteKey,
                        size: "invisible",
                        callback: (response) => {
                            this.emitVerifiedEvent(response);
                            this.reset();
                        },
                    });
                }
            },
            reset: function () {
                if (this.isEnabled) {
                    window.grecaptcha.reset(this.widgetId);
                }
            },
            emitVerifiedEvent: function (response) {
                this.$emit("verified", response);
            },
        },
        mounted: function () {
            this.render();
        }
    }
</script>
