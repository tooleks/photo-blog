<template>
    <div class="container py-3">
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
                            <button v-if="post"
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
                    <location-input :lat.sync="latitude" :lng.sync="longitude"></location-input>
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
    import FileInput from "../utils/file-input";
    import TagInput from "../utils/tag-input";
    import LocationInput from "../map/location-input";
    import PhotoCard from "./photo-card";
    import {GotoMixin, MetaMixin} from "../../mixins";
    import {apiService, mapperService, notificationService} from "../../services";
    import {optional} from "../../utils";

    export default {
        components: {
            LocationInput,
            FileInput,
            TagInput,
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
                latitude: undefined,
                longitude: undefined,
                post: undefined,
            };
        },
        computed: {
            postId: function () {
                return optional(() => this.post.id);
            },
            photoId: function () {
                return optional(() => this.post.photo.id);
            },
            photo: function () {
                return mapperService.map(this.post, "Api.V1.Post", "App.Photo");
            },
            pageTitle: function () {
                return optional(() => `Edit photo ${this.photo.description}`) || "Add photo";
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
                    const response = await apiService.getPost(id);
                    mapperService.map({response, component: this}, "Api.V1.Post", "App.Component.PhotoModify");
                } catch (error) {
                    if (optional(() => error.response.status) === 404) {
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
                        await apiService.deletePost(this.postId);
                        notificationService.success("The photo has been successfully deleted.");
                    } finally {
                        this.loading = false;
                        this.goToHomePage();
                    }
                }
            },
            savePost: async function () {
                const createPost = async (post) => await apiService.createPost(post);
                const updatePost = async (post) => await apiService.updatePost(post.id, post);
                const savePost = async (post) => optional(() => post.id) ? await updatePost(post) : await createPost(post);
                this.loading = true;
                try {
                    if (this.latitude && this.longitude) {
                        const photo = mapperService.map(this, "App.Component.PhotoModify", "Api.V1.Photo");
                        const photoResponse = await apiService.updatePhotoLocation(this.photoId, photo);
                        mapperService.map({response: photoResponse, component: this}, "Api.V1.Photo", "App.Component.PhotoModify");
                    }
                    const post = mapperService.map(this, "App.Component.PhotoModify", "Api.V1.Post");
                    const postResponse = await savePost(post);
                    mapperService.map({response: postResponse, component: this}, "Api.V1.Post", "App.Component.PhotoModify");
                    notificationService.success("The photo has been successfully saved.");
                } finally {
                    this.loading = false;
                }
            },
            uploadPhotoFile: async function (file) {
                this.loading = true;
                try {
                    const response = await apiService.uploadPhotoFile(file);
                    mapperService.map({response, component: this}, "Api.V1.Photo", "App.Component.PhotoModify");
                    notificationService.success("The photo has been successfully uploaded. Don't forget to save changes before exit.");
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
