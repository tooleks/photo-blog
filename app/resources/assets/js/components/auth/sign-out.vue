<template>
</template>

<script>
    import {GotoMixin} from "../../mixins";

    export default {
        mixins: [
            GotoMixin,
        ],
        methods: {
            signOut: async function () {
                try {
                    await this.$store.dispatch("auth/signOut");
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
