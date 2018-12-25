<template>
    <div class="container py-3">
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
                                <label for="password">{{ $lang("Password") }}
                                    <small>{{ $lang("Required") }}</small>
                                </label>
                                <input type="password"
                                       required
                                       id="password"
                                       class="form-control"
                                       v-model="password">
                            </div>
                            <div class="form-group">
                                <re-captcha ref="reCaptcha" @verified="signIn"/>
                            </div>
                            <button :disabled="loading" type="submit" class="btn btn-secondary">
                                {{ $lang("Sign In") }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import ReCaptcha from "../utils/ReCaptcha";
    import {AuthMixin, MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            ReCaptcha,
        },
        mixins: [
            AuthMixin,
            RouteMixin,
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {string} */
                email: "",
                /** @type {string} */
                password: "",
            };
        },
        methods: {
            async signIn(reCaptchaResponse) {
                this.loading = true;
                try {
                    const user = await this.$services.getLogin().signIn({
                        email: this.email,
                        password: this.password,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$services.getAlert().success(this.$lang("Hello {name}!", user.name));
                    this.goToRedirectUrl();
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
        created() {
            this.setPageTitle(this.$lang("Sign In"));
            if (this.authenticated) {
                this.goToRedirectUrl();
            }
        },
    }
</script>
