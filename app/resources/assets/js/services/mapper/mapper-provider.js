import Mapper from "./mapper";
import date from "../date";
import config from "../../config";
import router from "../../router";
import {optional} from "../../utils";

export default function () {
    const mapper = new Mapper;

    mapper.registerResolver("App.Meta", "Vue.MetaInfo", function (meta) {
        return {
            title: optional(() => meta.title),
            titleTemplate: optional(() => meta.title) ? `%s | ${optional(() => meta.name)}` : optional(() => meta.name),
            meta: [
                {vmid: "description", name: "description", content: optional(() => meta.description)},
                {vmid: "keywords", name: "keywords", content: optional(() => meta.keywords)},
                // Open Graph protocol properties.
                {vmid: "og:type", property: "og:type", content: "article"},
                {vmid: "og:url", property: "og:url", content: config.url.app + router.currentRoute.fullPath},
                {vmid: "og:site_name", property: "og:site_name", content: optional(() => meta.name)},
                {vmid: "og:description", property: "og:description", content: optional(() => meta.description)},
                {vmid: "og:image", property: "og:image", content: optional(() => meta.image)},
                {vmid: "og:title", property: "og:title", content: optional(() => meta.title)},
                // Twitter Cards properties.
                {vmid: "twitter:card", name: "twitter:card", content: "summary_large_image"},
                {vmid: "twitter:title", name: "twitter:title", content: optional(() => meta.title)},
                {vmid: "twitter:image", name: "twitter:image", content: optional(() => meta.image)},
            ],
        };
    });

    mapper.registerResolver("Api.V1.User", "App.User", function (user) {
        return {
            id: optional(() => user.id),
            name: optional(() => user.name),
        };
    });

    mapper.registerResolver("Api.V1.Post", "App.Photo", function (post) {
        const route = router.history.current;
        return {
            id: post.id,
            route: {
                name: "photo",
                params: {id: post.id},
                query: {
                    tag: route.params.tag || route.query.tag,
                    search_phrase: route.params.search_phrase || route.query.search_phrase,
                    page: route.params.page || route.query.page,
                },
            },
            description: optional(() => post.description),
            exif: mapper.map(optional(() => post.photo.exif), "Api.V1.Exif", "App.Exif"),
            averageColor: optional(() => post.photo.avg_color),
            thumbnail: mapper.map(optional(() => post.photo.thumbnails.medium), "Api.V1.Thumbnail", "App.Thumbnail"),
            original: mapper.map(optional(() => post.photo.thumbnails.large), "Api.V1.Thumbnail", "App.Thumbnail"),
            tags: post.tags.map((tag) => optional(() => tag.value)),
            toString: () => optional(() => post.photo.thumbnails.large.url),
            is: (image) => optional(() => image.id) === optional(() => post.id),
        };
    });

    mapper.registerResolver("*", "Api.V1.Post", function (any) {
        return {
            id: undefined,
            created_by_user_id: undefined,
            is_published: undefined,
            description: undefined,
            published_at: undefined,
            created_at: undefined,
            updated_at: undefined,
            photo: {
                id: undefined,
                created_by_user_id: undefined,
                avg_color: undefined,
                created_at: undefined,
                exif: {
                    manufacturer: undefined,
                    model: undefined,
                    exposure_time: undefined,
                    aperture: undefined,
                    iso: undefined,
                    taken_at: undefined
                },
                thumbnails: {
                    medium: {
                        url: undefined,
                        width: undefined,
                        height: undefined
                    },
                    large: {
                        url: undefined,
                        width: undefined,
                        height: undefined
                    }
                }
            },
            tags: [],
        };
    });

    mapper.registerResolver("Api.V1.Thumbnail", "App.Thumbnail", function (thumbnail) {
        return {
            url: optional(() => thumbnail.url),
            width: optional(() => thumbnail.width),
            height: optional(() => thumbnail.height),
        };
    });

    mapper.registerResolver("Api.V1.Exif", "App.Exif", function (exif) {
        return {
            manufacturer: optional(() => exif.manufacturer),
            model: optional(() => exif.model),
            exposureTime: optional(() => exif.exposure_time),
            aperture: optional(() => exif.aperture),
            iso: optional(() => exif.iso),
            takenAt: optional(() => exif.taken_at) ? date.format(exif.taken_at) : optional(() => exif.taken_at),
        };
    });

    mapper.registerResolver("Api.V1.Tag", "App.Tag", function (tag) {
        return optional(() => tag.value);
    });

    mapper.registerResolver("App.Tag", "Api.V1.Tag", function (tag) {
        return {
            value: tag,
        };
    });

    return mapper;
}
