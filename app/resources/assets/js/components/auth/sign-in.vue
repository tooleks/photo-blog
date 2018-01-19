<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="signIn">
                            <div class="form-group">
                                <label for="email">Email
                                    <small>Required</small>
                                </label>
                                <input type="email"
                                       id="email"
                                       class="form-control"
                                       v-model.trim="form.email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password
                                    <small>Required</small>
                                </label>
                                <input type="password"
                                       id="password"
                                       class="form-control"
                                       v-model="form.password">
                            </div>
                            <button :disabled="isPending" type="submit" class="btn btn-secondary">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {AuthMixin, GotoMixin, MetaMixin} from "../../mixins";
    import {notification} from "../../services";

    export default {
        mixins: [
            AuthMixin,
            GotoMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                form: {
                    email: "",
                    password: "",
                },
            };
        },
        computed: {
            isPending: function () {
                return this.$store.getters["auth/isPending"];
            },
            pageTitle: function () {
                return "Sign In";
            },
        },
        methods: {
            init: function () {
                if (this.isAuthenticated) {
                    this.goToPath(this.$route.query.redirect);
                }
            },
            signIn: function () {
                this.$store.dispatch("auth/signIn", this.form)
                    .then((user) => {
                        notification.success(`Hello, ${user.name}!`);
                        this.goToPath(this.$route.query.redirect);
                    });
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
