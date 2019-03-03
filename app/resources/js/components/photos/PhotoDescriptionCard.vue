<template>
    <div v-if="photo" class="card">
        <div class="card-header bg-white">
            <button @click="emitBackEvent" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $lang("Back to gallery") }}
            </button>
            <router-link v-if="authenticated && photo.published" :to="{name: 'photo/edit', params: {id: photo.postId}}"
                         class="btn btn-light btn-sm">
                <i class="fa fa-pencil" aria-hidden="true"></i> {{ $lang("Edit photo") }}
            </router-link>
            <share-buttons :url="pageCanonicalUrl" :title="pageTitle" class="float-sm-right"/>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <p v-if="photo.exif && photo.exif.takenAt" class="card-text">
                        {{ $lang("Taken on {date}", photo.exif.takenAt.format("LLLL")) }}
                    </p>
                    <p class="card-text" :title="$lang('Description')">{{ photo.description }}</p>
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
                                    :disabled="true"/>
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
    import LocationInput from "../map/LocationInput";
    import ExifDescription from "./ExifDescription";
    import ShareButtons from "../utils/ShareButtons";
    import TagBadges from "../utils/TagBadges";
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
            canonicalUrl: {
                type: String,
            },
        },
        methods: {
            emitBackEvent() {
                this.$emit("onBack");
            },
        },
    }
</script>
