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
