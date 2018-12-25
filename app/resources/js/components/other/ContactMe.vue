<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            {{ $lang("If you have any questions just send a message in the form below.") }}
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
                                        <label for="name">{{ $lang("Name") }}
                                            <small>{{ $lang("Required") }}</small>
                                        </label>
                                        <input type="text"
                                               required
                                               id="name"
                                               class="form-control"
                                               v-model.trim="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">{{ $lang("Subject") }}
                                            <small>{{ $lang("Required") }}</small>
                                        </label>
                                        <input type="text"
                                               required
                                               id="subject"
                                               class="form-control"
                                               v-model.trim="subject">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">{{ $lang("Message") }}
                                            <small>{{ $lang("Required") }}</small>
                                        </label>
                                        <textarea id="message"
                                                  required
                                                  class="form-control"
                                                  v-model.trim="message"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <re-captcha ref="reCaptcha" @verified="contactMe"/>
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
                /** @type {string} */
                name: "",
                /** @type {string} */
                subject: "",
                /** @type {string} */
                message: "",
            };
        },
        methods: {
            async contactMe(reCaptchaResponse) {
                this.loading = true;
                try {
                    await this.$services.getApi().createContactMessage({
                        email: this.email,
                        name: this.name,
                        subject: this.subject,
                        message: this.message,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$services.getAlert().success(this.$lang("Your message has been successfully sent."));
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
            this.setPageTitle(this.$lang("Contact Me"));
        },
    }
</script>
