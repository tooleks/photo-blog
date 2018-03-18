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
                                        <re-captcha ref="reCaptcha" @verified="subscribe"></re-captcha>
                                    </div>
                                    <button :disabled="loading" type="submit" class="btn btn-secondary">Send</button>
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
    import ReCaptcha from "../utils/re-captcha";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {apiService, notificationService} from "../../services";

    export default {
        components: {
            ReCaptcha,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                email: "",
            };
        },
        computed: {
            pageTitle: function () {
                return "Subscription";
            },
        },
        methods: {
            subscribe: async function (reCaptchaResponse) {
                this.loading = true;
                try {
                    await apiService.createSubscription({
                        email: this.email,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    notificationService.success("You have been successfully subscribed to the website updates.");
                    this.goToHomePage();
                } finally {
                    this.loading = false;
                }
            },
        },
    }
</script>
