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
                                    <div class="form-group">
                                        <label for="name">Name
                                            <small>Required</small>
                                        </label>
                                        <input type="text"
                                               id="name"
                                               class="form-control"
                                               v-model.trim="form.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="subject">Subject
                                            <small>Required</small>
                                        </label>
                                        <input type="text"
                                               id="subject"
                                               class="form-control"
                                               v-model.trim="form.subject">
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message
                                            <small>Required</small>
                                        </label>
                                        <textarea id="message"
                                                  class="form-control"
                                                  v-model.trim="form.message"
                                                  rows="3"></textarea>
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
                    name: "",
                    subject: "",
                    message: "",
                },
            };
        },
        computed: {
            isPending: function () {
                return this.$store.getters["contactMessage/isPending"];
            },
            pageTitle: function () {
                return "Contact Me";
            },
        },
        methods: {
            send: function () {
                this.$store.dispatch("contactMessage/createContactMessage", this.form)
                    .then(() => {
                        notification.success("Your message has been successfully sent.");
                        this.goToHomePage();
                    });
            },
        },
    }
</script>
