<template>
</template>

<script>
    import {GotoMixin} from "../../mixins";
    import {loginService} from "../../services";

    export default {
        mixins: [
            GotoMixin,
        ],
        methods: {
            signOut: async function () {
                try {
                    await loginService.signOut();
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
