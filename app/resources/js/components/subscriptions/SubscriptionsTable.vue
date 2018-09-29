<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <table class="table table-bordered bg-white">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th>Unsubscribe</th>
                        <th class="width-3em"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="subscription in subscriptions">
                        <td>{{ subscription.email }}</td>
                        <td>
                            <router-link
                                    :to="{name: 'unsubscription', params: {token: subscription.token}}"
                                    target="_blank">
                                {{ subscription.token }}
                            </router-link>
                        </td>
                        <td class="width-3em text-center">
                            <button @click="deleteSubscription(subscription)"
                                    class="btn btn-secondary btn-sm"
                                    type="button"
                                    aria-label="Delete"
                                    title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="!loading && !subscriptions.length" class="row">
            <div class="col mt-3">
                <div class="alert alert-secondary">No subscriptions found</div>
            </div>
        </div>
        <div v-if="previousPageExists || nextPageExists" class="row">
            <div class="col mt-2">
                <router-link
                        v-if="previousPageExists"
                        :to="{name: this.routeName, params: {page: this.previousPage}}"
                        class="btn btn-secondary float-left"
                        title="Previous Page">Previous
                </router-link>
                <router-link
                        v-if="nextPageExists"
                        :to="{name: this.routeName, params: {page: this.nextPage}}"
                        class="btn btn-secondary float-right"
                        title="Next Page">Next
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import {MetaMixin} from "../../mixins";
    import {_PAGINATION} from "../../router/names";

    export default {
        mixins: [
            MetaMixin,
        ],
        data: function () {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {Array<Subscription>} */
                subscriptions: [],
                /** @type {number|null} */
                previousPage: null,
                /** @type {number} */
                currentPage: 1,
                /** @type {number|null} */
                nextPage: null,
                /** @type {boolean} */
                previousPageExists: false,
                /** @type {boolean} */
                nextPageExists: false,
            };
        },
        computed: {
            routeName: function () {
                return this.$route.name.endsWith(_PAGINATION) ? this.$route.name : `${this.$route.name}${_PAGINATION}`;
            },
            pageTitle: function () {
                return "Subscriptions";
            },
        },
        watch: {
            "$route": function () {
                this.loadSubscriptions();
            },
            currentPage: function (currentPage) {
                if (currentPage > 1) {
                    this.$router.push({name: this.routeName, params: {page: currentPage}});
                }
            },
        },
        methods: {
            setSubscriptions: function ({items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage}) {
                this.subscriptions = items;
                this.previousPageExists = previousPageExists;
                this.nextPageExists = nextPageExists;
                this.currentPage = currentPage;
                this.nextPage = nextPage;
                this.previousPage = previousPage;
            },
            loadSubscriptions: async function (page = this.$route.params.page) {
                this.loading = true;
                try {
                    this.setSubscriptions(await this.$services.getSubscriptionManager().paginate({page}));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
            deleteSubscription: async function (subscription) {
                if (confirm(`Do you really want to delete the ${subscription.email} subscription?`)) {
                    this.loading = true;
                    try {
                        await this.$services.getSubscriptionManager().deleteByToken(subscription.token);
                        this.$services.getAlert().success("The subscription has been successfully deleted.");
                        await this.loadSubscriptions();
                    } catch (error) {
                        // The error is handled by the API service.
                        // No additional actions needed.
                    } finally {
                        this.loading = false;
                    }
                }
            },
        },
        created: function () {
            this.loadSubscriptions();
        },
    };
</script>
