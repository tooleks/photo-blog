<template>
    <div class="container py-3">
        <loader :loading="loading"></loader>
        <div class="row">
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="savePost">
                            <div class="form-group">
                                <label for="message">Description
                                    <small>Required</small>
                                </label>
                                <textarea id="message"
                                          required
                                          class="form-control"
                                          v-model.trim="description"
                                          :disabled="loading"
                                          rows="7"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tags">Tags
                                    <small>Required</small>
                                </label>
                                <tag-input :tags.sync="tags"
                                           :attributes="{id: 'tags', class: 'form-control', rows: '5', required: true, disabled: loading}"></tag-input>
                            </div>
                            <button :disabled="loading" type="submit" class="btn btn-secondary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                            </button>
                            <file-input @change="uploadPhotoFile"
                                        :attributes="{id: 'file', name: 'file', class: 'btn btn-secondary', disabled: loading}">
                                <i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload file
                            </file-input>
                            <button v-if="postId"
                                    @click="deletePost"
                                    :disabled="loading"
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
                <div v-if="photo" class="card mt-3">
                    <location-input :location.sync="location"></location-input>
                </div>
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
    import {optional as opt} from "tooleks";
    import FileInput from "../utils/file-input";
    import Loader from "../utils/loader";
    import TagInput from "../utils/tag-input";
    import LocationInput from "../map/location-input";
    import PhotoCard from "./photo-card";
    import {GotoMixin, MetaMixin} from "../../mixins";

    export default {
        components: {
            FileInput,
            Loader,
            TagInput,
            LocationInput,
            PhotoCard,
        },
        mixins: [
            GotoMixin,
            MetaMixin,
        ],
        data: function () {
            return {
                loading: false,
                description: "",
                tags: [],
                location: null,
                post: null,
            };
        },
        computed: {
            postId: function () {
                return opt(() => this.post.id);
            },
            photoId: function () {
                return opt(() => this.post.photo.id);
            },
            photo: function () {
                return this.$dc.get("mapper").map(this.post, "Api.Post", "Photo");
            },
            pageTitle: function () {
                const description = opt(() => this.photo.description);
                return description ? `Edit photo ${description}` : "Add photo";
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
        },
        methods: {
            init: function () {
                if (this.$route.params.id) {
                    this.loadPost();
                }
            },
            loadPost: async function (id = this.$route.params.id) {
                this.loading = true;
                try {
                    const response = await this.$dc.get("api").getPost(id);
                    this.$dc.get("mapper").map({response, component: this}, "Api.Raw.Post", "Component.PhotoModify");
                } catch (error) {
                    if (opt(() => error.response.status) === 404) {
                        this.goToNotFoundPage();
                    } else {
                        throw error;
                    }
                } finally {
                    this.loading = false;
                }
            },
            deletePost: async function () {
                if (confirm("Do you really want to delete the photo?")) {
                    this.loading = true;
                    try {
                        await this.$dc.get("api").deletePost(this.postId);
                        this.$dc.get("notification").success("The photo has been successfully deleted.");
                    } finally {
                        this.loading = false;
                        this.goToHomePage();
                    }
                }
            },
            savePost: async function () {
                const createPost = async (post) => await this.$dc.get("api").createPost(post);
                const updatePost = async (post) => await this.$dc.get("api").updatePost(post.id, post);
                const savePost = async (post) => opt(() => post.id) ? await updatePost(post) : await createPost(post);
                this.loading = true;
                try {
                    if (this.location) {
                        const photo = this.$dc.get("mapper").map(this, "Component.PhotoModify", "Api.Photo");
                        const response = await this.$dc.get("api").updatePhotoLocation(this.photoId, photo);
                        this.$dc.get("mapper").map({response, component: this}, "Api.Raw.Photo", "Component.PhotoModify");
                    }
                    const post = this.$dc.get("mapper").map(this, "Component.PhotoModify", "Api.Post");
                    const response = await savePost(post);
                    this.$dc.get("mapper").map({response, component: this}, "Api.Raw.Post", "Component.PhotoModify");
                    this.$dc.get("notification").success("The photo has been successfully saved.");
                } finally {
                    this.loading = false;
                }
            },
            uploadPhotoFile: async function (file) {
                this.loading = true;
                try {
                    const response = await this.$dc.get("api").uploadPhotoFile(file);
                    this.$dc.get("mapper").map({response, component: this}, "Api.Raw.Photo", "Component.PhotoModify");
                    this.$dc.get("notification").success("The photo has been successfully uploaded. Don't forget to save changes before exit.");
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
