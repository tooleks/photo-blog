<template>
    <div class="location">
        <span class="value">{{ formattedLat }}, {{ formattedLng }}</span>
        <span class="value" :title="address">{{ address }}</span>
    </div>
</template>

<style lang="scss" scoped>
    .location {
        .value {
            display: block;
        }
    }
</style>

<script>
    export default {
        props: {
            lat: {type: Number},
            lng: {type: Number},
            address: {type: String},
        },
        computed: {
            formattedLat: function () {
                return this.lat.toFixed(4);
            },
            formattedLng: function () {
                return this.lng.toFixed(4);
            },
        },
    };
</script>
