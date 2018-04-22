import {Mapper, optional} from "tooleks";
import router from "../../router";

export default function (dateService) {
    const mapperService = new Mapper;

    mapperService.registerResolver("Api.V1.User", "App.User", function (user) {
        if (typeof user === "undefined") {
            return undefined;
        }

        return {
            id: optional(() => user.id),
            name: optional(() => user.name),
            expires_at: (new Date).setSeconds(optional(() => user.expires_in, 0)).valueOf()
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

    mapperService.registerResolver("Api.V1.Post", "App.Component.PhotoModify", function ({response, component}) {
        const post = optional(() => response.data);
        component.post = optional(() => post);
        component.description = optional(() => post.description);
        component.tags = optional(() => post.tags, []).map((tag) => mapperService.map(tag, "Api.V1.Tag", "App.Tag"));
        component.latitude = optional(() => post.photo.location.latitude);
        component.longitude = optional(() => post.photo.location.longitude);
        return component;
    });

    mapperService.registerResolver("Api.V1.Photo", "App.Component.PhotoModify", function ({response, component}) {
        const photo = optional(() => response.data);
        component.post = component.post || {};
        component.post.photo = optional(() => photo);
        return component;
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

    mapperService.registerResolver("App.Tag", "Api.V1.Tag", function (tag) {
        if (typeof tag === "undefined") {
            return undefined;
        }

        return {
            value: tag,
        };
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

    mapperService.registerResolver("App.Component.PhotoModify", "Api.V1.Post", function (component) {
        return {
            id: optional(() => component.postId),
            photo: {id: optional(() => component.photoId)},
            description: optional(() => component.description),
            tags: optional(() => component.tags, []).map((tag) => mapperService.map(tag, "App.Tag", "Api.V1.Tag")),
        };
    });

    mapperService.registerResolver("App.Component.PhotoModify", "Api.V1.Photo", function (component) {
        return {
            location: {
                latitude: optional(() => component.latitude),
                longitude: optional(() => component.longitude),
            },
        };
    });

    mapperService.registerResolver("App.PhotoService.Photos", "App.Component.PhotoGallery", function ({photos, component}) {
        component.photos = optional(() => photos.items);
        component.previousPage = optional(() => photos.previousPage);
        component.currentPage = optional(() => photos.currentPage);
        component.nextPage = optional(() => photos.nextPage);
        component.previousPageExists = optional(() => photos.previousPageExists);
        component.nextPageExists = optional(() => photos.nextPageExists);
        return component;
    });

    return mapperService;
};
