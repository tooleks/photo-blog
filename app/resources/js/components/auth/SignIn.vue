<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="$refs.reCaptcha.verify">
                            <div class="form-group">
                                <label for="email">Email
                                    <small>Required</small>
                                </label>
                                <input type="email"
                                       required
                                       id="email"
                                       class="form-control"
                                       v-model.trim="email">
                            </div>
                            <div class="form-group">
                                <label for="password">Password
                                    <small>Required</small>
                                </label>
                                <input type="password"
                                       required
                                       id="password"
                                       class="form-control"
                                       v-model="password">
                            </div>
                            <div class="form-group">
                                <re-captcha ref="reCaptcha" @verified="signIn"></re-captcha>
                            </div>
                            <button :disabled="loading" type="submit" class="btn btn-secondary">Sign In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ReCaptcha from "../utils/ReCaptcha";
    import {AuthMixin, GoToMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            ReCaptcha,
        },
        mixins: [
            AuthMixin,
            GoToMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                email: "",
                password: "",
            };
        },
        computed: {
            pageTitle: function () {
                return "Sign In";
            },
        },
        methods: {
            init: function () {
                if (this.authenticated) {
                    this.goToPath(this.$route.query.redirect_uri);
                }
            },
            signIn: async function (reCaptchaResponse) {
                this.loading = true;
                try {
                    const user = await this.$dc.get("login").signIn({
                        email: this.email,
                        password: this.password,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$dc.get("notification").success(`Hello ${user.name}!`);
                    this.goToPath(this.$route.query.redirect_uri);
                } finally {
                    this.loading = false;
                }
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
