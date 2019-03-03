import {optional} from "tooleks";
import Exif from "../entities/Exif";
import Image from "../entities/Image";
import Location from "../entities/Location";
import Photo from "../entities/Photo";
import Subscription from "../entities/Subscription";
import Tag from "../entities/Tag";
import User from "../entities/User";

/**
 * @param {Object} attributes
 * @param {string} attributes.value
 * @returns {Tag}
 */
export function toTag({value}) {
    return new Tag({value});
}

/**
 * @param {Object} attributes
 * @param {number} attributes.id
 * @param {string} attributes.name
 * @returns {User}
 */
export function toUser({id, name}) {
    return new User({id, name});
}

/**
 * @param {Object} attributes
 * @param {string} attributes.url
 * @param {number} attributes.width
 * @param {number} attributes.height
 * @returns {Image}
 */
export function toImage({url, width, height}) {
    return new Image({url, width, height});
}

/**
 * @param {Object} attributes
 * @param {string} attributes.manufacturer
 * @param {string} attributes.model
 * @param {string} attributes.exposure_time
 * @param {string} attributes.aperture
 * @param {string} attributes.focal_length
 * @param {string} attributes.iso
 * @param {string} attributes.taken_at
 * @param {string} attributes.software
 * @returns {Exif}
 */
export function toExif({manufacturer, model, exposure_time, aperture, focal_length, focal_length_in_35_mm, iso, taken_at, software}) {
    return new Exif({
        manufacturer,
        model,
        exposureTime: exposure_time,
        aperture,
        focalLength: focal_length,
        focalLengthIn35mm: focal_length_in_35_mm,
        iso,
        takenAt: taken_at,
        software: software,
    });
}

/**
 * @param {Object} attributes
 * @param {number} attributes.latitude
 * @param {number} attributes.longitude
 * @returns {Location}
 */
export function toLocation({latitude, longitude}) {
    return new Location({
        lat: latitude,
        lng: longitude,
    });
}

/**
 * @param {Object} attributes
 * @param {string} [attributes.id]
 * @param {string} [attributes.description]
 * @param {Array<Object>} [attributes.tags]
 * @param {Object} attributes.photo
 * @returns {Photo}
 */
export function toPhoto({id, description = "", tags = [], photo}) {
    return new Photo({
        postId: id,
        id: photo.id,
        description,
        tags: tags.map((attributes) => toTag(attributes)),
        thumbnail: toImage(photo.thumbnails.medium),
        original: toImage(photo.thumbnails.large),
        exif: toExif(photo.exif),
        averageColor: photo.avg_color,
        location: optional(() => toLocation(photo.location)),
    });
}

/**
 * @param {Object} attributes
 * @param {string} attributes.email
 * @param {string} attributes.token
 * @returns {Subscription}
 */
export function toSubscription({email, token}) {
    return new Subscription({email, token});
}

/**
 * @param {Object} body
 * @param {Array<Object>} body.data
 * @param {Function} transform
 * @returns {Array<*>}
 */
export function toList(body, transform) {
    return body.data.map((attributes) => transform(attributes));
}

/**
 * @param {Object} body
 * @param {Array<Object>} body.data
 * @param {string|null} body.prev_page_url
 * @param {string|null} body.next_page_url
 * @param {number} body.current_page
 * @param {Function} transform
 * @returns {Object}
 */
export function toPaginator(body, transform) {
    const items = body.data.map((attributes) => transform(attributes));
    const previousPageExists = Boolean(body.prev_page_url);
    const nextPageExists = Boolean(body.next_page_url);
    const currentPage = Number(body.current_page);
    const nextPage = nextPageExists ? currentPage + 1 : null;
    const previousPage = previousPageExists ? currentPage - 1 : null;
    return {items, previousPageExists, nextPageExists, currentPage, nextPage, previousPage};
}
