<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            Would you like to unsubscribe from receiving the website updates?
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-danger" @click="send" :disabled="isPending">
                            Yes
                        </button>
                        <button type="button" class="btn btn-secondary" @click="goToHomePage" :disabled="isPending">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {notification} from "../../services";

    export default {
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        computed: {
            isPending: function () {
                return this.$store.getters["subscription/isPending"];
            },
            pageTitle: function () {
                return "Unsubscription";
            },
        },
        methods: {
            send: async function () {
                try {
                    await this.$store.dispatch("subscription/deleteSubscription", {token: this.$route.params.token});
                    notification.success("You have been successfully unsubscribed from the website updates.");
                } finally {
                    this.goToHomePage();
                }
            },
        },
    }
</script>
