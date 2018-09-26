/**
 * Remove undefined keys from the object.
 *
 * @param {Object} object
 * @return {void}
 */
export function removeUndefinedKeys(object) {
    Object.keys(object).forEach((key) => {
        if (typeof object[key] === "undefined") {
            delete object[key];
        }
    });
}
