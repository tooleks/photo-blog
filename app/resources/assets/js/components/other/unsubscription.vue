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
                        <button type="button" class="btn btn-danger" @click="unsubscribe" :disabled="loading">
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
    import {GotoMixin, MetaMixin} from "../../mixins";

    export default {
        mixins: [
            GotoMixin,
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
            unsubscribe: async function () {
                this.loading = true;
                try {
                    await this.$dc.get("api").deleteSubscription(this.$route.params.token);
                    this.$dc.get("notification").success("You have been successfully unsubscribed from the website updates.");
                } finally {
                    this.loading = false;
                    this.goToHomePage();
                }
            },
        },
    }
</script>
