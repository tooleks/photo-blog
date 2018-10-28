<template>
    <div :id="id"></div>
</template>

<style>
    .grecaptcha-badge {
        z-index: 1 !important;
        bottom: 0 !important;
    }
</style>

<script>
    export default {
        props: {
            id: {
                type: String,
                default() {
                    const id = Math.random().toString(36).substr(2, 5);
                    return `g-recaptcha-${id}`;
                },
            },
            siteKey: {
                type: String,
                default() {
                    return this.$services.getConfig().credentials.googleReCaptcha.siteKey;
                },
            },
        },
        data() {
            return {
                /** @type {BrowserReCaptcha|DummyReCaptcha} */
                reCaptcha: this.$services.getReCaptcha(this.id, this.siteKey, (response) => this.$emit("verified", response)),
            };
        },
        methods: {
            async verify() {
                await this.reCaptcha.execute();
            },
        },
        async mounted() {
            await this.reCaptcha.render();
        },
    }
</script>
