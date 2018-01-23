import Mapper from "./mapper";
import date from "../date";
import config from "../../config";
import router from "../../router";
import {value} from "../../utils";

export default function () {
    const mapper = new Mapper;

    mapper.registerResolver("App.Meta", "Vue.MetaInfo", function (meta) {
        return {
            title: value(() => meta.title),
            titleTemplate: value(() => meta.title) ? `%s | ${value(() => meta.name)}` : value(() => meta.name),
            meta: [
                {vmid: "description", name: "description", content: value(() => meta.description)},
                {vmid: "keywords", name: "keywords", content: value(() => meta.keywords)},
                // Open Graph protocol properties.
                {vmid: "og:type", property: "og:type", content: "article"},
                {vmid: "og:url", property: "og:url", content: config.url.app + router.currentRoute.fullPath},
                {vmid: "og:site_name", property: "og:site_name", content: value(() => meta.name)},
                {vmid: "og:description", property: "og:description", content: value(() => meta.description)},
                {vmid: "og:image", property: "og:image", content: value(() => meta.image)},
                {vmid: "og:title", property: "og:title", content: value(() => meta.title)},
                // Twitter Cards properties.
                {vmid: "twitter:card", name: "twitter:card", property: "twitter:card", content: "summary_large_image"},
                {vmid: "twitter:title", name: "twitter:title", property: "twitter:title", content: value(() => meta.title)},
                {vmid: "twitter:image", name: "twitter:image", property: "twitter:image", content: value(() => meta.image)},
            ],
        };
    });

    mapper.registerResolver("Api.V1.User", "App.User", function (user) {
        return {
            id: value(() => user.id),
            name: value(() => user.name),
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
            description: value(() => post.description),
            exif: mapper.map(value(() => post.photo.exif), "Api.V1.Exif", "App.Exif"),
            averageColor: value(() => post.photo.avg_color),
            thumbnail: mapper.map(value(() => post.photo.thumbnails.medium), "Api.V1.Thumbnail", "App.Thumbnail"),
            original: mapper.map(value(() => post.photo.thumbnails.large), "Api.V1.Thumbnail", "App.Thumbnail"),
            tags: post.tags.map((tag) => value(() => tag.value)),
            toString: () => value(() => post.photo.thumbnails.large.url),
            is: (image) => value(() => image.id) === value(() => post.id),
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
            url: value(() => thumbnail.url),
            width: value(() => thumbnail.width),
            height: value(() => thumbnail.height),
        };
    });

    mapper.registerResolver("Api.V1.Exif", "App.Exif", function (exif) {
        return {
            manufacturer: value(() => exif.manufacturer),
            model: value(() => exif.model),
            exposureTime: value(() => exif.exposure_time),
            aperture: value(() => exif.aperture),
            iso: value(() => exif.iso),
            takenAt: value(() => exif.taken_at) ? date.format(exif.taken_at) : value(() => exif.taken_at),
        };
    });

    mapper.registerResolver("Api.V1.Tag", "App.Tag", function (tag) {
        return value(() => tag.value);
    });

    mapper.registerResolver("App.Tag", "Api.V1.Tag", function (tag) {
        return {
            value: tag,
        };
    });

    return mapper;
}
