<template>
</template>

<script>
    import {GotoMixin} from "../../mixins";

    export default {
        mixins: [
            GotoMixin,
        ],
        methods: {
            signOut: function () {
                this.$store.dispatch("auth/signOut")
                    .then(() => {
                        this.goToSignInPage();
                    })
                    .catch(() => {
                        this.goToSignInPage();
                    });
            },
        },
        created: function () {
            this.signOut();
        },
    }
</script>
