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
                        <button type="button" class="btn btn-danger" @click="deleteSubscription()" :disabled="loading">
                            Yes
                        </button>
                        <button type="button" class="btn btn-secondary" @click="goToHomePage" :disabled="loading">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {GoToMixin, MetaMixin} from "../../mixins";

    export default {
        mixins: [
            GoToMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
            };
        },
        computed: {
            pageTitle: function () {
                return "Unsubscription";
            },
        },
        methods: {
            deleteSubscription: async function (token = this.$route.params.token) {
                this.loading = true;
                try {
                    await this.$services.getSubscriptionManager().deleteByToken(token);
                    this.$services.getAlert().success("You have been successfully unsubscribed from the website updates.");
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                    this.goToHomePage();
                }
            },
        },
    }
</script>
