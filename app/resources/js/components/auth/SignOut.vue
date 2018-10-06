<template>
</template>

<script>
    import {RouteMixin} from "../../mixins";

    export default {
        mixins: [
            RouteMixin,
        ],
        methods: {
            async signOut() {
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
        created() {
            this.signOut();
        },
    }
</script>
