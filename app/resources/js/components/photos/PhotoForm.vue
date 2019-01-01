<template>
    <div class="container py-3">
        <round-spinner :loading="loading" :delay="0"/>
        <file-input id="file"
                    class="btn"
                    required="true"
                    :disabled="loading"
                    @select="uploadPhoto"/>
        <div v-if="photo.image" class="card mt-3">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <photo-card :photo="photo"/>
                            </div>
                            <div class="col mt-sm-3 mt-md-0">
                                <location-input :location.sync="photo.location"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <form @submit.prevent="savePhoto">
                            <div class="form-group">
                                <label for="message">{{ $lang("Description") }}
                                    <small>{{ $lang("Required") }}</small>
                                </label>
                                <textarea id="message"
                                          required
                                          class="form-control"
                                          v-model.trim="photo.description"
                                          :disabled="loading"
                                          rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tags">{{ $lang("Tags") }}
                                    <small>{{ $lang("Required") }}</small>
                                </label>
                                <tag-input id="tags"
                                           class="form-control"
                                           rows="3"
                                           required="true"
                                           :disabled="loading"
                                           :tags.sync="photo.tags"></tag-input>
                                <small class="form-text text-muted">{{ $lang("Comma-separated tag values.") }}</small>
                            </div>
                            <button :disabled="loading" type="submit" class="btn btn-secondary">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> {{ $lang("Save") }}
                            </button>
                            <button v-if="photo.published"
                                    @click="deletePhoto"
                                    :disabled="loading"
                                    type="button"
                                    class="btn btn-danger float-right">
                                <i class="fa fa-trash" aria-hidden="true"></i> {{ $lang("Delete") }}
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
    import {optional} from "tooleks";
    import FileInput from "../utils/FileInput";
    import RoundSpinner from "../utils/RoundSpinner";
    import TagInput from "../utils/TagInput";
    import LocationInput from "../map/LocationInput";
    import PhotoCard from "./PhotoCard";
    import {MetaMixin, RouteMixin} from "../../mixins";

    export default {
        components: {
            FileInput,
            RoundSpinner,
            TagInput,
            LocationInput,
            PhotoCard,
        },
        mixins: [
            RouteMixin,
            MetaMixin,
        ],
        data() {
            return {
                /** @type {boolean} */
                loading: false,
                /** @type {Photo|Object} */
                photo: {},
            };
        },
        methods: {
            async loadPhoto(id = this.$route.params.id) {
                this.loading = true;
                try {
                    this.photo = await this.$services.getPhotoManager().getByPostId(id);
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
            async uploadPhoto(file) {
                this.loading = true;
                try {
                    const photo = await this.$services.getPhotoManager().upload(file);
                    // Replace the photo image or the whole photo if there is no uploaded image yet.
                    if (this.photo.image) {
                        this.photo.replaceImage(photo);
                    } else {
                        this.photo = photo;
                    }
                    this.$services.getAlert().success(this.$lang("The photo has been successfully uploaded. Don't forget to save changes before exit."));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
            async savePhoto() {
                this.loading = true;
                try {
                    this.photo = await this.$services.getPhotoManager().publish(this.photo);
                    this.$services.getAlert().success(this.$lang("The photo has been successfully saved."));
                } catch (error) {
                    // The error is handled by the API service.
                    // No additional actions needed.
                } finally {
                    this.loading = false;
                }
            },
            async deletePhoto() {
                if (confirm("Do you really want to delete the photo?")) {
                    this.loading = true;
                    try {
                        await this.$services.getPhotoManager().deleteByPostId(this.photo.postId);
                        this.$services.getAlert().success(this.$lang("The photo has been successfully deleted."));
                        this.goToHomePage();
                    } catch (error) {
                        // The error is handled by the API service.
                        // No additional actions needed.
                    } finally {
                        this.loading = false;
                    }
                }
            },
        },
        created() {
            if (this.$route.params.id) {
                this.loadPhoto();
            }
        },
    }
</script>
