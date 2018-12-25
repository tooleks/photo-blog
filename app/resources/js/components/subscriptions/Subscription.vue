<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            {{ $lang("Sign up with your email address to receive the website updates.") }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <form @submit.prevent="$refs.reCaptcha.verify">
                                    <div class="form-group">
                                        <label for="email">{{ $lang("Email") }}
                                            <small>{{ $lang("Required") }}</small>
                                        </label>
                                        <input type="email"
                                               required
                                               id="email"
                                               class="form-control"
                                               v-model.trim="email"
                                               v-focus>
                                    </div>
                                    <div class="form-group">
                                        <re-captcha ref="reCaptcha" @verified="subscribe"/>
                                    </div>
                                    <button :disabled="loading" type="submit" class="btn btn-secondary">
                                        {{ $lang("Send") }}
                                    </button>
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
    import ReCaptcha from "../utils/ReCaptcha";
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            ReCaptcha,
        },
        mixins: [
            RouteMixin,
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {string} */
                email: "",
            };
        },
        methods: {
            async subscribe(reCaptchaResponse) {
                this.loading = true;
                try {
                    await this.$services.getApi().createSubscription({
                        email: this.email,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$services.getAlert().success(this.$lang("You have been successfully subscribed to the website updates."));
                    this.goToHomePage();
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        created() {
            this.setPageTitle(this.$lang("Subscription"));
        },
    }
</script>
