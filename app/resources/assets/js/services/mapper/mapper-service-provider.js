import MapperService from "./mapper-service";
import dateService from "../date";
import router from "../../router";
import {optional} from "../../utils";

export default function () {
    const mapperService = new MapperService;

    mapperService.registerResolver("Api.V1.User", "App.User", function (user) {
        if (typeof user === "undefined") {
            return undefined;
        }

        return {
            id: optional(() => user.id),
            name: optional(() => user.name),
        };
    });

    mapperService.registerResolver("Api.V1.Post", "App.Photo", function (post) {
        if (typeof post === "undefined") {
            return undefined;
        }

        const route = router.history.current;
        return {
            id: optional(() => post.id),
            route: {
                name: "photo",
                params: {id: optional(() => post.id)},
                query: {
                    tag: route.params.tag || route.query.tag,
                    search_phrase: route.params.search_phrase || route.query.search_phrase,
                    page: route.params.page || route.query.page,
                },
            },
            description: optional(() => post.description),
            exif: mapperService.map(optional(() => post.photo.exif), "Api.V1.Exif", "App.Exif"),
            averageColor: optional(() => post.photo.avg_color),
            thumbnail: mapperService.map(optional(() => post.photo.thumbnails.medium), "Api.V1.Thumbnail", "App.Thumbnail"),
            original: mapperService.map(optional(() => post.photo.thumbnails.large), "Api.V1.Thumbnail", "App.Thumbnail"),
            tags: optional(() => post.tags.map((tag) => optional(() => tag.value)), []),
            location: mapperService.map(optional(() => post.photo.location), "Api.V1.Location", "App.Location"),
            toString: () => optional(() => post.photo.thumbnails.large.url),
            is: (image) => optional(() => image.id) === optional(() => post.id),
        };
    });

    mapperService.registerResolver("Api.V1.Post", "App.Map.Image", function (post) {
        if (typeof post === "undefined") {
            return undefined;
        }

        return {
            imageUrl: optional(() => post.photo.thumbnails.large.url),
            linkUrl: optional(() => `/photo/${post.id}`),
            title: optional(() => post.description),
            location: mapperService.map(optional(() => post.photo.location), "Api.V1.Location", "App.Location"),
        };
    });

    mapperService.registerResolver("Api.V1.Thumbnail", "App.Thumbnail", function (thumbnail) {
        if (typeof thumbnail === "undefined") {
            return undefined;
        }

        return {
            url: optional(() => thumbnail.url),
            width: optional(() => thumbnail.width),
            height: optional(() => thumbnail.height),
        };
    });

    mapperService.registerResolver("Api.V1.Exif", "App.Exif", function (exif) {
        if (typeof exif === "undefined") {
            return undefined;
        }

        return {
            manufacturer: optional(() => exif.manufacturer),
            model: optional(() => exif.model),
            exposureTime: optional(() => exif.exposure_time),
            aperture: optional(() => exif.aperture),
            iso: optional(() => exif.iso),
            takenAt: optional(() => exif.taken_at) ? dateService.format(exif.taken_at) : optional(() => exif.taken_at),
        };
    });

    mapperService.registerResolver("Api.V1.Tag", "App.Tag", function (tag) {
        if (typeof tag === "undefined") {
            return undefined;
        }

        return optional(() => tag.value);
    });

    mapperService.registerResolver("App.Tag", "Api.V1.Tag", function (tag) {
        if (typeof tag === "undefined") {
            return undefined;
        }

        return {
            value: tag,
        };
    });

    mapperService.registerResolver("Api.V1.Location", "App.Location", function (location) {
        if (typeof location === "undefined") {
            return undefined;
        }

        return optional(() => {
            return {
                lat: location.latitude,
                lng: location.longitude,
            };
        });
    });

    mapperService.registerResolver("App.Location", "Api.V1.Location", function (location) {
        if (typeof location === "undefined") {
            return undefined;
        }

        return optional(() => {
            return {
                latitude: location.lat,
                longitude: location.lng,
            };
        });
    });

    return mapperService;
}
