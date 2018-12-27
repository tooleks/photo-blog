<template>
    <table v-if="exif" class="table table-responsive table-striped table-sm d-table w-100">
        <tbody>
        <tr v-if="exif.manufacturer">
            <th class="w-50 pl-3">{{ $lang("Manufacturer") }}</th>
            <td class="w-50 pr-3">{{ exif.manufacturer }}</td>
        </tr>
        <tr v-if="exif.model">
            <th class="w-50 pl-3">{{ $lang("Model") }}</th>
            <td class="w-50 pr-3">{{ exif.model }}</td>
        </tr>
        <tr v-if="exif.exposureTime">
            <th class="w-50 pl-3">{{ $lang("Exposure time") }}</th>
            <td class="w-50 pr-3">{{ exif.exposureTime }}</td>
        </tr>
        <tr v-if="exif.aperture">
            <th class="w-50 pl-3">{{ $lang("Aperture") }}</th>
            <td class="w-50 pr-3">{{ exif.aperture }}</td>
        </tr>
        <tr v-if="exif.focalLength">
            <th class="w-50 pl-3">{{ $lang("Focal length") }}</th>
            <td class="w-50 pr-3">{{ $lang("{value}mm", exif.focalLength) }}</td>
        </tr>
        <tr v-if="exif.focalLengthIn35mm">
            <th class="w-50 pl-3">{{ $lang("Focal length (35mm equivalent)") }}</th>
            <td class="w-50 pr-3">{{ $lang("{value}mm", exif.focalLengthIn35mm) }}</td>
        </tr>
        <tr v-if="exif.iso">
            <th class="w-50 pl-3">{{ $lang("ISO") }}</th>
            <td class="w-50 pr-3">{{ exif.iso }}</td>
        </tr>
        <tr v-if="exif.software">
            <th class="w-50 pl-3">{{ $lang("Software") }}</th>
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
