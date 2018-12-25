<template>
    <div class="location">
        <span class="value">{{ lat.toFixed(4) }}, {{ lng.toFixed(4) }}</span>
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
    };
</script>
