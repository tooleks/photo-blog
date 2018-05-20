<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <table class="table table-bordered bg-white">
                    <thead>
                    <tr>
                        <th>Email</th>
                        <th class="width-3em"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="subscription in subscriptions">
                        <td>{{ subscription.email }}</td>
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

    export default {
        mixins: [
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                subscriptions: [],
                previousPage: null,
                currentPage: 1,
                nextPage: null,
                previousPageExists: false,
                nextPageExists: false,
            }
        },
        computed: {
            routeName: function () {
                const withPageSuffix = "-with-page";
                return this.$route.name.endsWith(withPageSuffix) ? this.$route.name : `${this.$route.name}${withPageSuffix}`;
            },
            pageTitle: function () {
                return "Subscriptions";
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
            currentPage: function (currentPage) {
                if (currentPage > 1) {
                    this.$router.push({name: this.routeName, params: {page: currentPage}});
                }
            },
        },
        methods: {
            init: async function () {
                this.loadSubscriptions();
            },
            loadSubscriptions: async function () {
                this.loading = true;
                try {
                    const page = this.$route.params.page;
                    const response = await this.$dc.get("api").getSubscriptions({page});
                    this.$dc.get("mapper").map({response, component: this}, "Api.Raw.Subscriptions", "Component");
                } finally {
                    this.loading = false;
                }
            },
            deleteSubscription: async function (subscription) {
                if (confirm(`Do you really want to delete the ${subscription.email} subscription?`)) {
                    this.loading = true;
                    try {
                        await this.$dc.get("api").deleteSubscription(subscription.token);
                        this.$dc.get("notification").success("The subscription has been successfully deleted.");
                        await this.init();
                    } finally {
                        this.loading = false;
                    }
                }
            },
        },
        created: function () {
            this.init();
        },
    };
</script>
