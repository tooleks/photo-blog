<template>
    <div id="g-recaptcha"></div>
</template>

<script>
    import config from "../../config";
    import {provideReCaptchaService} from "../../services";

    export default {
        props: {
            siteKey: {
                type: String,
                default: config.credentials.googleReCaptcha.siteKey,
            },
        },
        data: function () {
            return {
                reCaptcha: provideReCaptchaService("g-recaptcha", this.siteKey, (response) => this.$emit("verified", response)),
            };
        },
        methods: {
            verify: function () {
                this.reCaptcha.execute();
            },
        },
        mounted: function () {
            if (this.reCaptcha.isReady()) {
                this.reCaptcha.render();
            }
        },
    }
</script>
