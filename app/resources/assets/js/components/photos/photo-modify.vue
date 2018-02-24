<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="savePhoto">
                            <div class="form-group">
                                <label for="tags">Tags
                                    <small>Required</small>
                                </label>
                                <tag-input :tags.sync="formTags"
                                           :attributes="{id: 'tags', class: 'form-control', rows: '5', required: true}"></tag-input>
                            </div>
                            <div class="form-group">
                                <label for="message">Description
                                    <small>Required</small>
                                </label>
                                <textarea id="message"
                                          required
                                          class="form-control"
                                          v-model.trim="formDescription"
                                          rows="7"></textarea>
                            </div>
                            <button :disabled="isPending" type="submit" class="btn btn-secondary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                            </button>
                            <file-input @change="uploadFile"
                                        :attributes="{id: 'file', name: 'file', class: 'btn btn-secondary', disabled: isPending}">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload file
                            </file-input>
                            <button v-if="photo.id"
                                    @click="deletePhoto"
                                    :disabled="isPending"
                                    type="button"
                                    class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg mt-3 mt-lg-0">
                <photo-card :photo="photo"></photo-card>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .btn {
        margin-right: 2px;
        margin-bottom: 6px;
    }

    .btn:last-child {
        margin-right: 0;
    }
</style>

<script>
    import FileInput from "../utils/file-input";
    import TagInput from "../utils/tag-input";
    import PhotoCard from "./photo-card";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {mapper, notification} from "../../services";
    import {optional} from "../../utils";

    export default {
        components: {
            FileInput,
            TagInput,
            PhotoCard,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        computed: {
            isPending: function () {
                return this.$store.getters["photoForm/isPending"];
            },
            formTags: {
                set: function (tags) {
                    this.$store.commit("photoForm/setTags", {tags});
                },
                get: function () {
                    return this.$store.getters["photoForm/getTags"];
                },
            },
            formDescription: {
                set: function (description) {
                    this.$store.commit("photoForm/setDescription", {description});
                },
                get: function () {
                    return this.$store.getters["photoForm/getDescription"];
                },
            },
            photo: function () {
                return this.$store.getters["photoForm/getPhoto"];
            },
            pageTitle: function () {
                return optional(() => this.photo.description) || "Add photo";
            },
            pageImage: function () {
                return optional(() => this.photo.original.url);
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
        },
        methods: {
            init: function () {
                this.reset();
                if (optional(() => this.$route.params.id)) {
                    this.loadPhoto(this.$route.params.id);
                }
            },
            reset: function () {
                this.$store.commit("photoForm/reset");
            },
            loadPhoto: async function (id) {
                try {
                    await this.$store.dispatch("photoForm/loadPhoto", {id});
                } catch (error) {
                    if (optional(() => error.response.status) === 404) {
                        this.goTo404Page();
                    } else {
                        this.goToHomePage();
                    }
                }
            },
            savePhoto: async function () {
                await this.$store.dispatch("photoForm/savePhoto");
                notification.success("The photo has been successfully saved.");
            },
            deletePhoto: async function () {
                if (confirm("Do you really want to delete the photo?")) {
                    await this.$store.dispatch("photoForm/deletePhoto");
                    notification.success("The photo has been successfully deleted.");
                    this.goToHomePage();
                }
            },
            uploadFile: async function (file) {
                await this.$store.dispatch("photoForm/uploadFile", {file});
                notification.success("The photo has been successfully uploaded. Don't forget to save changes before exit.");
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
