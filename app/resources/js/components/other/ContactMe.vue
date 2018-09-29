<template>
    <div class="container py-3">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <p class="alert alert-secondary">
                            If you have any questions just send a message in the form below.
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
                                               v-model.trim="email"
                                               v-focus>
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Name
                                            <small>Required</small>
                                        </label>
                                        <input type="text"
                                               required
                                               id="name"
                                               class="form-control"
                                               v-model.trim="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">Subject
                                            <small>Required</small>
                                        </label>
                                        <input type="text"
                                               required
                                               id="subject"
                                               class="form-control"
                                               v-model.trim="subject">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message
                                            <small>Required</small>
                                        </label>
                                        <textarea id="message"
                                                  required
                                                  class="form-control"
                                                  v-model.trim="message"
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <re-captcha ref="reCaptcha" @verified="contactMe"></re-captcha>
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
    import ReCaptcha from "../utils/ReCaptcha";
    import {GoToMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            ReCaptcha,
        },
        mixins: [
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
                name: "",
                /** @type {string} */
                subject: "",
                /** @type {string} */
                message: "",
            };
        },
        computed: {
            pageTitle: function () {
                return "Contact Me";
            },
        },
        methods: {
            contactMe: async function (reCaptchaResponse) {
                this.loading = true;
                try {
                    await this.$services.getApi().createContactMessage({
                        email: this.email,
                        name: this.name,
                        subject: this.subject,
                        message: this.message,
                        g_recaptcha_response: reCaptchaResponse,
                    });
                    this.$services.getAlert().success("Your message has been successfully sent.");
                    this.goToHomePage();
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
        },
    }
</script>
