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
                    await this.$dc.get("login").signOut();
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
