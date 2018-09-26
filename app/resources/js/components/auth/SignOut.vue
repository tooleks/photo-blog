<template>
</template>

<script>
    import {GoToMixin} from "../../mixins";

    export default {
        mixins: [
            GoToMixin,
        ],
        methods: {
            signOut: async function () {
                try {
                    await this.$services.getLogin().signOut();
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.goToSignInPage();
                }
            },
        },
        created: function () {
            this.signOut();
        },
    }
</script>
