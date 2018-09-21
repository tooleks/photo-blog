import {isUndefined} from "tooleks";

/**
 * Remove undefined keys from the object.
 *
 * @param {Object} object
 * @return {void}
 */
export function removeUndefinedKeys(object) {
    Object.keys(object).forEach((key) => {
        if (isUndefined(object[key])) {
            delete object[key];
        }
    });
}
