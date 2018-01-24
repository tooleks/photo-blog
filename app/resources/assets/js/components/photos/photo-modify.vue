<template>
    <div class="container py-3">
        <div class="row">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="savePhoto">
                            <div class="form-group">
                                <label for="tags">Tags
                                    <small>Required</small>
                                </label>
                                <tag-input :tags.sync="formTags"
                                           :attributes="{id: 'tags', class: 'form-control', rows: '5'}"></tag-input>
                            </div>
                            <div class="form-group">
                                <label for="message">Description
                                    <small>Required</small>
                                </label>
                                <textarea id="message"
                                          class="form-control"
                                          v-model.trim="formDescription"
                                          rows="7"></textarea>
                            </div>
                            <button :disabled="isPending" type="submit" class="btn btn-secondary">Save</button>
                            <file-input @change="uploadFile"
                                        :text="'Upload file'"
                                        :attributes="{id: 'file', name: 'file', class: 'btn btn-secondary', disabled: isPending}"></file-input>
                            <button v-if="photo.id"
                                    @click="deletePhoto"
                                    :disabled="isPending"
                                    type="button"
                                    class="btn btn-secondary">Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md mt-3 mt-md-0">
                <photo-card :photo="photo"></photo-card>
            </div>
        </div>
    </div>
</template>

<script>
    import FileInput from "../layout/file-input";
    import TagInput from "../layout/tag-input";
    import PhotoCard from "./photo-card";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {mapper, notification} from "../../services";
    import {value} from "../../utils";

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
                return value(() => this.photo.description) || "Add photo";
            },
            pageImage: function () {
                return value(() => this.photo.original.url);
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
                if (value(() => this.$route.params.id)) {
                    this.loadPhoto(this.$route.params.id);
                }
            },
            reset: function () {
                this.$store.commit("photoForm/reset");
            },
            loadPhoto: function (id) {
                this.$store
                    .dispatch("photoForm/loadPhoto", {id})
                    .catch((error) => {
                        if (value(() => error.response.status) === 404) {
                            this.goTo404Page();
                        } else {
                            this.goToHomePage();
                        }
                    });
            },
            savePhoto: function () {
                this.$store.dispatch("photoForm/savePhoto").then(() => {
                    notification.success("The photo has been successfully saved.");
                });
            },
            deletePhoto: function () {
                if (confirm('Do you really want to delete the photo?')) {
                    this.$store
                        .dispatch("photoForm/deletePhoto")
                        .then(() => {
                            notification.success("The photo has been successfully deleted.");
                            this.goToHomePage();
                        });
                }
            },
            uploadFile: function (file) {
                this.$store
                    .dispatch("photoForm/uploadFile", {file})
                    .then(() => {
                        notification.success("The photo has been successfully uploaded. Don't forget to save changes before exit.");
                    });
            },
        },
        created: function () {
            this.init();
        },
    }
</script>
