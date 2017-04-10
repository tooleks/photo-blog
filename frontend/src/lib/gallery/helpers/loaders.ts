/**
 * Call the callback function after an image was loaded.
 *
 * Note: This function actually loads the image only in the browser environment.
 *
 * @param {string} imageUrl
 * @param {function} callback
 */
export function loadImage(imageUrl:string, callback:any) {
    // Browser-specific logic.
    if (typeof (window) !== 'undefined') {
        const image = new Image;
        image.onload = callback;
        image.src = imageUrl;
    }
    // Server-specific logic.
    else {
        callback();
    }
}
