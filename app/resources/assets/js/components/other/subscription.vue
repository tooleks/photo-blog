<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            Sign up with your email address to receive the website updates.
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="send">
                                    <div class="form-group">
                                        <label for="email">Email
                                            <small>Required</small>
                                        </label>
                                        <input type="text"
                                               id="email"
                                               class="form-control"
                                               v-model.trim="form.email">
                                    </div>
                                    <button :disabled="isPending" type="submit" class="btn btn-secondary">Send</button>
                                </form>
                            </div>
                        </div>
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
        data: function () {
            return {
                form: {
                    email: "",
                },
            };
        },
        computed: {
            isPending: function () {
                return this.$store.getters["subscription/isPending"];
            },
            pageTitle: function () {
                return "Subscription";
            },
        },
        methods: {
            send: function () {
                this.$store.dispatch("subscription/createSubscription", this.form)
                    .then(() => {
                        notification.success("You have been successfully subscribed to the website updates.");
                        this.goToHomePage();
                    });
            },
        },
    }
</script>
