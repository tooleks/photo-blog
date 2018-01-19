export function preloadImage(url) {
    return new Promise((resolve, reject) => {
        const image = new Image;
        image.onload = (event) => resolve(url);
        image.onerror = (error) => reject(error);
        image.src = url;
    });
}
