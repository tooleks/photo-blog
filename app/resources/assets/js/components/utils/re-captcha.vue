<template>
    <div :id="this.id"></div>
</template>

<script>
    import config from "../../config";
    import {provideReCaptchaService} from "../../services";

    export default {
        props: {
            id: {
                type: String,
                default: () => {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `g-recaptcha-${id}`;
                },
            },
            siteKey: {
                type: String,
                default: config.credentials.googleReCaptcha.siteKey,
            },
        },
        data: function () {
            return {
                reCaptcha: provideReCaptchaService(this.id, this.siteKey, (response) => this.$emit("verified", response)),
            };
        },
        methods: {
            verify: function () {
                this.reCaptcha.execute();
            },
        },
        mounted: function () {
            this.reCaptcha.load();
            this.reCaptcha.render();
        },
    }
</script>
