<template>
    <table v-if="exif" class="table table-responsive table-striped table-sm d-table w-100">
        <tbody>
        <tr v-if="exif.manufacturer">
            <th class="w-50 pl-3">Manufacturer</th>
            <td class="w-50 pr-3">{{ exif.manufacturer }}</td>
        </tr>
        <tr v-if="exif.model">
            <th class="w-50 pl-3">Model</th>
            <td class="w-50 pr-3">{{ exif.model }}</td>
        </tr>
        <tr v-if="exif.exposureTime">
            <th class="w-50 pl-3">Exposure Time</th>
            <td class="w-50 pr-3">{{ exif.exposureTime }}</td>
        </tr>
        <tr v-if="exif.aperture">
            <th class="w-50 pl-3">Aperture</th>
            <td class="w-50 pr-3">{{ exif.aperture }}</td>
        </tr>
        <tr v-if="exif.iso">
            <th class="w-50 pl-3">ISO</th>
            <td class="w-50 pr-3">{{ exif.iso }}</td>
        </tr>
        <tr v-if="exif.iso">
            <th class="w-50 pl-3">Software</th>
            <td class="w-50 pr-3">{{ exif.software }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    import Exif from "../../entities/Exif";

    export default {
        props: {
            exif: Exif,
        },
    }
</script>
