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

    // TODO: Refactor the following component.
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
                return optional(() => `Edit photo "${this.photo.description}"`) || "Add photo";
            },
        },
        watch: {
            "$route": function () {
                this.init();
            },
            latitude: function (latitude) {
                if (latitude && this.longitude) {
                    this.savePhotoLocation();
                }
            },
            longitude: function (longitude) {
                if (this.latitude && longitude) {
                    this.savePhotoLocation();
                }
            },
        },
        methods: {
            init: async function () {
                if (this.$route.params.id) {
                    await this.loadPost();
                    this.description = this.post.description;
                    this.tags = this.post.tags.map((tag) => mapperService.map(tag, "Api.V1.Tag", "App.Tag"));
                    this.latitude = optional(() => this.post.photo.location.latitude);
                    this.longitude = optional(() => this.post.photo.location.longitude);
                }
            },
            loadPost: async function (id = this.$route.params.id) {
                this.loading = true;
                try {
                    const {data} = await apiService.getPost(id);
                    this.post = data;
                    return data;
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
                const create = async (post) => await apiService.createPost(post);
                const update = async (post) => await apiService.updatePost(post.id, post);
                const save = async (post) => optional(() => post.id) ? await update(post) : await create(post);
                this.loading = true;
                try {
                    const {data} = await save({
                        id: this.postId,
                        photo: {id: this.photoId},
                        description: this.description,
                        tags: this.tags.map((tag) => mapperService.map(tag, "App.Tag", "Api.V1.Tag")),
                    });
                    this.post = data;
                    notificationService.success("The photo has been successfully saved.");
                } finally {
                    this.loading = false;
                }
            },
            savePhotoLocation: async function () {
                const {data} = await apiService.updatePhotoLocation(this.photoId, {
                    location: {latitude: this.latitude, longitude: this.longitude},
                });
                this.post = this.post || {};
                this.post.photo = data;
            },
            uploadPhotoFile: async function (file) {
                this.loading = true;
                try {
                    const {data} = await apiService.uploadPhotoFile(file);
                    this.post = this.post || {};
                    this.post.photo = data;
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
