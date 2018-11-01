import {optional} from "tooleks";
import Photo from "../entities/Photo";
import Tag from "../entities/Tag";

/**
 * @param {Tag} tag
 * @return {Object}
 */
export function toTag(tag) {
    return {
        value: tag.value,
    };
}

/**
 * @param {Location} location
 * @return {Object}
 */
export function toLocation(location) {
    return {
        latitude: location.lat,
        longitude: location.lng,
    };
}

/**
 * @param {Photo} photo
 * @return {Object}
 */
export function toPhoto(photo) {
    return {
        id: photo.id,
        location: optional(() => toLocation(photo.location)),
    };
}

/**
 * @param {Photo} photo
 * @return {Object}
 */
export function toPost(photo) {
    return {
        photo: toPhoto(photo),
        description: photo.description,
        tags: photo.tags.map((tag) => toTag(tag)),
    };
}
