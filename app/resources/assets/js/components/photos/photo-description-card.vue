<template>
    <div class="card" v-if="photo">
        <div class="card-header bg-white">
            <button @click="emitBackEvent" class="btn btn-light btn-sm">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to gallery
            </button>
            <a :href="photo" class="btn btn-light btn-sm" target="_blank">
                <i class="fa fa-expand" aria-hidden="true"></i> Open in full screen
            </a>
            <router-link v-if="isAuthenticated" :to="{name: 'photo/edit', params: {id: photo.id}}"
                         class="btn btn-light btn-sm">
                <i class="fa fa-pencil" aria-hidden="true"></i> Edit photo
            </router-link>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md">
                    <p class="card-text" v-if="photo.exif.takenAt !== 'N/A'">
                        Taken on {{ photo.exif.takenAt }}
                    </p>
                    <p class="card-text" title="Description">{{ photo.description }}</p>
                    <tag-badges :tags="photo.tags"></tag-badges>
                </div>
                <div class="col-md mt-3 mt-md-0">
                    <exif-description :exif="photo.exif"></exif-description>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <share-buttons></share-buttons>
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
    import ExifDescription from "./exif-description";
    import ShareButtons from "../layout/share-buttons";
    import {AuthMixin} from "../../mixins"
    import TagBadges from "./tag-badges";

    export default {
        components: {
            ExifDescription,
            ShareButtons,
            TagBadges,
        },
        mixins: [
            AuthMixin,
        ],
        props: {
            photo: Object,
        },
        methods: {
            emitBackEvent: function () {
                this.$emit("onBack");
            },
        },
    }
</script>
