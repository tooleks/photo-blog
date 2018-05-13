<template>
    <div v-if="photo" class="card">
        <div class="card-header bg-white">
            <button @click="emitBackEvent" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to gallery
            </button>
            <router-link v-if="authenticated && photo.id" :to="{name: 'photo/edit', params: {id: photo.id}}"
                         class="btn btn-light btn-sm">
                <i class="fa fa-pencil" aria-hidden="true"></i> Edit photo
            </router-link>
            <share-buttons class="float-sm-right"></share-buttons>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <p v-if="photo.exif && photo.exif.takenAt" class="card-text">
                        Taken on {{ photo.exif.takenAt }}
                    </p>
                    <p class="card-text" title="Description">{{ photo.description }}</p>
                    <tag-badges :tags="photo.tags"></tag-badges>
                </div>
                <div class="col-md mt-3 mt-md-0">
                    <exif-description :exif="photo.exif"></exif-description>
                </div>
            </div>
            <div v-if="photo.location" class="row">
                <div class="col mt-3">
                    <location-input :location="photo.location"
                                    :zoom="12"
                                    :disabled="true"></location-input>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .card {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }

    .btn {
        margin-right: 2px;
        margin-bottom: 6px;
    }

    .btn:last-child {
        margin-right: 0;
    }
</style>

<script>
    import LocationInput from "../map/location-input";
    import ExifDescription from "./exif-description";
    import ShareButtons from "../utils/share-buttons";
    import TagBadges from "../utils/tag-badges";
    import {AuthMixin} from "../../mixins"

    export default {
        components: {
            LocationInput,
            ExifDescription,
            ShareButtons,
            TagBadges,
        },
        mixins: [
            AuthMixin,
        ],
        props: {
            photo: {
                type: Object,
            },
        },
        methods: {
            emitBackEvent: function () {
                this.$emit("onBack");
            },
        },
    }
</script>
