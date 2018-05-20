<template>
    <div class="container py-3">
        <loader :loading="loading" :delay="0"></loader>
        <file-input :attributes="{id: 'file', name: 'file', disabled: loading}"
                    @change="uploadPhotoFile"></file-input>
        <div v-show="photo" class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div v-if="photo" class="row">
                            <div class="col">
                                <photo-card :photo="photo"></photo-card>
                            </div>
                            <div class="col mt-sm-3 mt-md-0">
                                <location-input :location.sync="location"></location-input>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="photo" class="row mt-3">
                    <div class="col">
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
                                          rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tags">Tags
                                    <small>Required</small>
                                </label>
                                <tag-input :tags.sync="tags"
                                           :attributes="{id: 'tags', class: 'form-control', rows: '3', required: true, disabled: loading}"></tag-input>
                            </div>
                            <button :disabled="loading" type="submit" class="btn btn-secondary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                            </button>
                            <button v-if="postId"
                                    @click="deletePost"
                                    :disabled="loading"
                                    type="button"
                                    class="btn btn-danger float-right">
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                            </button>
                        </form>
                    </div>
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
                description: null,
                tags: [],
                location: null,
                post: null,
            };
        },
        computed: {
            postId: function () {
                return opt(() => this.post.id, null);
            },
            photoId: function () {
                return opt(() => this.post.photo.id, null);
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
            post: function (post) {
                const description = opt(() => post.description);
                const tags = opt(() => post.tags, []).map((tag) => this.$dc.get("mapper").map(tag, "Api.Tag", "Tag"));
                const location = opt(() => {
                    return {
                        lat: post.photo.location.latitude,
                        lng: post.photo.location.longitude,
                    };
                }, null);

                this.description = description;
                this.tags = tags;
                this.location = location;
            },
        },
        methods: {
            init: function () {
                if (this.$route.params.id) {
                    this.loadPost();
                }
            },
            setPost: function (post) {
                this.post = post;
            },
            setPostPhoto: function (photo) {
                this.post = this.post || {};
                this.$set(this.post, "photo", photo);
            },
            loadPost: async function (id = this.$route.params.id) {
                this.loading = true;
                try {
                    const {data: post} = await this.$dc.get("api").getPost(id);
                    this.setPost(post);
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
                if (this.location) {
                    await this.updatePostPhotoLocation();
                }
                if (this.postId) {
                    await this.updatePost();
                } else {
                    await this.createPost();
                }
            },
            createPost: async function () {
                this.loading = true;
                try {
                    const formData = this.$dc.get("mapper").map(this, "Component.PhotoForm", "Api.Post.FormData");
                    const {data: post} = await this.$dc.get("api").createPost(formData);
                    this.setPost(post);
                    this.$dc.get("notification").success("The photo has been successfully saved.");
                } finally {
                    this.loading = false;
                }
            },
            updatePost: async function () {
                this.loading = true;
                try {
                    const formData = this.$dc.get("mapper").map(this, "Component.PhotoForm", "Api.Post.FormData");
                    const {data: post} = await this.$dc.get("api").updatePost(this.postId, formData);
                    this.setPost(post);
                    this.$dc.get("notification").success("The photo has been successfully saved.");
                } finally {
                    this.loading = false;
                }
            },
            updatePostPhotoLocation: async function () {
                this.loading = true;
                try {
                    const formData = this.$dc.get("mapper").map(this, "Component.PhotoForm", "Api.Photo.FormData");
                    const {data: photo} = await this.$dc.get("api").updatePhotoLocation(this.photoId, formData);
                    this.setPostPhoto(photo);
                } finally {
                    this.loading = false;
                }
            },
            uploadPhotoFile: async function (file) {
                this.loading = true;
                try {
                    const {data: photo} = await this.$dc.get("api").uploadPhotoFile(file);
                    this.setPostPhoto(photo);
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
