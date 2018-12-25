<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            {{ $lang("Would you like to unsubscribe from receiving the website updates?") }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-danger" @click="deleteSubscription()" :disabled="loading">
                            {{ $lang("Yes") }}
                        </button>
                        <button type="button" class="btn btn-secondary" @click="goToHomePage" :disabled="loading">
                            {{ $lang("No") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        mixins: [
            RouteMixin,
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
            };
        },
        methods: {
            async deleteSubscription(token = this.$route.params.token) {
                this.loading = true;
                try {
                    await this.$services.getSubscriptionManager().deleteByToken(token);
                    this.$services.getAlert().success(this.$lang("You have been successfully unsubscribed from the website updates."));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                    this.goToHomePage();
                }
            },
        },
        created() {
            this.setPageTitle(this.$lang("Unsubscribe"));
        },
    }
</script>
