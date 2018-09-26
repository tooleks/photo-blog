<template>
    <div :id="id"></div>
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
            this.siteKey = this.siteKey || this.$services.getConfig().credentials.googleReCaptcha.siteKey;
            return {
                reCaptcha: this.$services.getReCaptcha(this.id, this.siteKey, (response) => this.$emit("verified", response)),
            };
        },
        methods: {
            verify: async function () {
                await this.reCaptcha.execute();
            },
        },
        mounted: async function () {
            await this.reCaptcha.load();
            await this.reCaptcha.render();
        },
    }
</script>
