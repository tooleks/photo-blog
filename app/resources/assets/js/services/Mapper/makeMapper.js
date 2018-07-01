import moment from "moment";
import {Mapper, optional as opt} from "tooleks";
import router from "../../router";

export default function (dateService) {
    const mapperService = new Mapper;

    mapperService.registerResolver("Api.User", "User", function (user) {
        return {
            id: user.id,
            name: user.name,
            expires_at: moment.utc().add(user.expires_in, "seconds"),
        };
    });

    mapperService.registerResolver("Api.Post", "Photo", function (post) {
        if (typeof post === "undefined" || post === null) {
            return null;
        }

        const route = router.history.current;
        return {
            id: opt(() => post.id),
            route: {
                name: "photo",
                params: {id: opt(() => post.id)},
                query: {
                    tag: route.params.tag || route.query.tag,
                    search_phrase: route.params.search_phrase || route.query.search_phrase,
                    page: route.params.page || route.query.page,
                },
            },
            description: opt(() => post.description),
            exif: mapperService.map(opt(() => post.photo.exif), "Api.Exif", "Exif"),
            averageColor: opt(() => post.photo.avg_color),
            thumbnail: mapperService.map(opt(() => post.photo.thumbnails.medium), "Api.Thumbnail", "Thumbnail"),
            original: mapperService.map(opt(() => post.photo.thumbnails.large), "Api.Thumbnail", "Thumbnail"),
            tags: opt(() => post.tags, []).map((tag) => mapperService.map(tag, "Api.Tag", "Tag")),
            location: mapperService.map(opt(() => post.photo.location), "Api.Location", "Location"),
            toString: () => opt(() => post.photo.thumbnails.large.url),
            is: (image) => opt(() => image.id) === opt(() => post.id),
        };
    });

    mapperService.registerResolver("Api.Post", "Map.Image", function (post) {
        return {
            imageUrl: opt(() => post.photo.thumbnails.large.url),
            linkUrl: opt(() => `/photo/${post.id}`),
            title: opt(() => post.description),
            location: mapperService.map(opt(() => post.photo.location), "Api.Location", "Location"),
        };
    });

    mapperService.registerResolver("Api.Thumbnail", "Thumbnail", function (thumbnail) {
        return {
            url: opt(() => thumbnail.url),
            width: opt(() => thumbnail.width),
            height: opt(() => thumbnail.height),
        };
    });

    mapperService.registerResolver("Api.Exif", "Exif", function (exif) {
        return {
            manufacturer: opt(() => exif.manufacturer),
            model: opt(() => exif.model),
            exposureTime: opt(() => exif.exposure_time),
            aperture: opt(() => exif.aperture),
            iso: opt(() => exif.iso),
            takenAt: opt(() => exif.taken_at) ? dateService.format(exif.taken_at) : opt(() => exif.taken_at),
        };
    });

    mapperService.registerResolver("Api.Tag", "Tag", function (tag) {
        return opt(() => tag.value);
    });

    mapperService.registerResolver("Api.Subscription", "Subscription", function (subscription) {
        return {
            email: opt(() => subscription.email),
            token: opt(() => subscription.token),
        };
    });

    mapperService.registerResolver("Api.Location", "Location", function (location) {
        if (typeof location === "undefined" || location === null) {
            return null;
        }

        return {
            lat: opt(() => location.latitude),
            lng: opt(() => location.longitude),
        };
    });

    mapperService.registerResolver("Tag", "Api.Tag", function (tag) {
        return {
            value: tag,
        };
    });

    mapperService.registerResolver("Location", "Api.Location", function (location) {
        if (typeof location === "undefined" || location === null) {
            return null;
        }

        return {
            latitude: opt(() => location.lat),
            longitude: opt(() => location.lng),
        };
    });

    mapperService.registerResolver("Api.Raw.Posts", "Meta.Photos", function (response) {
        const {data: body} = response;
        const items = body.data.map((post) => mapperService.map(post, "Api.Post", "Photo"));
        const previousPageExists = Boolean(body.prev_page_url);
        const nextPageExists = Boolean(body.next_page_url);
        const currentPage = Number(body.current_page);
        const nextPage = nextPageExists ? currentPage + 1 : null;
        const previousPage = previousPageExists ? currentPage - 1 : null;
        return {items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage};
    });

    mapperService.registerResolver("Api.Raw.Subscriptions", "Meta.Subscriptions", function (response) {
        const {data: body} = response;
        const items = body.data.map((subscription) => mapperService.map(subscription, "Api.Subscription", "Subscription"));
        const previousPageExists = Boolean(body.prev_page_url);
        const nextPageExists = Boolean(body.next_page_url);
        const currentPage = Number(body.current_page);
        const nextPage = nextPageExists ? currentPage + 1 : null;
        const previousPage = previousPageExists ? currentPage - 1 : null;
        return {items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage};
    });

    mapperService.registerResolver("Api.Raw.Tags", "Meta.Tags", function (response) {
        const {data: body} = response;
        const items = body.data.map((tag) => mapperService.map(tag, "Api.Tag", "Tag"));
        const previousPageExists = Boolean(body.prev_page_url);
        const nextPageExists = Boolean(body.next_page_url);
        const currentPage = Number(body.current_page);
        const nextPage = nextPageExists ? currentPage + 1 : null;
        const previousPage = previousPageExists ? currentPage - 1 : null;
        return {items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage};
    });

    //

    mapperService.registerResolver("Component.PhotoForm", "Api.Post.FormData", function (component) {
        return {
            id: opt(() => component.postId),
            photo: {id: opt(() => component.photoId)},
            description: opt(() => component.description),
            tags: component.tags.map((tag) => mapperService.map(tag, "Tag", "Api.Tag")),
        };
    });

    mapperService.registerResolver("Component.PhotoForm", "Api.Photo.FormData", function (component) {
        return {
            location: {
                latitude: opt(() => component.location.lat),
                longitude: opt(() => component.location.lng),
            },
        };
    });

    return mapperService;
};
