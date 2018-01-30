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
                                        <input type="text"
                                               id="email"
                                               class="form-control"
                                               v-model.trim="form.email">
                                    </div>
                                    <div class="form-group">
                                        <re-captcha ref="reCaptcha" @verified="send"></re-captcha>
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
    import ReCaptcha from "../utils/re-captcha";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {notification} from "../../services";

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
            send: function (reCaptchaResponse) {
                this.$store.dispatch("subscription/createSubscription", Object.assign({}, this.form, {"g_recaptcha_response": reCaptchaResponse}))
                    .then(() => {
                        notification.success("You have been successfully subscribed to the website updates.");
                        this.goToHomePage();
                    });
            },
        },
    }
</script>
