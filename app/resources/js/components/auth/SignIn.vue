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
                                       v-model.trim="email"
                                       v-focus>
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
                /** @type {boolean} */
                loading: false,
                /** @type {string} */
                email: "",
                /** @type {string} */
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
                if (this.currentUser) {
                    this.goToRedirectUri();
                }
            },
            signIn: async function (reCaptchaResponse) {
                this.loading = true;
                try {
                    const user = await this.$services.getLogin().signIn({
                        email: this.email,
                        password: this.password,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$services.getAlert().success(`Hello ${user.name}!`);
                    this.goToRedirectUri();
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
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
