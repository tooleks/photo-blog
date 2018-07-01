<template>
    <div :id="this.id"></div>
</template>

<script>
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
                default: "",
            },
        },
        data: function () {
            this.siteKey = this.siteKey || this.$dc.get("config").credentials.googleReCaptcha.siteKey;
            return {
                reCaptcha: this.$dc.get("reCaptchaProvider")(this.id, this.siteKey, (response) => this.$emit("verified", response)),
            };
        },
        methods: {
            verify: function () {
                this.reCaptcha.execute();
            },
        },
        mounted: async function () {
            await this.reCaptcha.load();
            await this.reCaptcha.render();
        },
    }
</script>
