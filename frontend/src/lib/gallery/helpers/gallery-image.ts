import {GalleryImage} from '../models';
import {scale, sum} from './value';

/**
 * Scale the image small size by a given scale rate.
 *
 * @param {GalleryImage} image
 * @param {number} scaleRate
 * @return {GalleryImage}
 */
export function scaleImageSmallSize(image:GalleryImage, scaleRate:number):GalleryImage {
    let smallImageScaledSizeWidth = scale(image.getSmallSizeWidth(), scaleRate);
    let smallImageScaledSizeHeight = scale(image.getSmallSizeHeight(), scaleRate);

    return image
        .clone()
        .setSmallSizeWidth(Math.floor(smallImageScaledSizeWidth))
        .setSmallSizeHeight(Math.floor(smallImageScaledSizeHeight));
}

/**
 * Scale images small size by a given scale rate.
 *
 * @param {Array<GalleryImage>} images
 * @param {number} scaleRate
 * @return {Array<GalleryImage>}
 */
export function scaleImagesSmallSize(images:Array<GalleryImage>, scaleRate:number):Array<GalleryImage> {
    return images.map((image:GalleryImage) => scaleImageSmallSize(image, scaleRate));
}

/**
 * Scale the image small size to a given height value.
 *
 * @param {GalleryImage} image
 * @param {number} height
 * @return {GalleryImage}
 */
export function scaleImageSmallSizeToHeight(image:GalleryImage, height:number):GalleryImage {
    const scaleRate = image.getSmallSizeHeight() * 100 / height;
    return scaleImageSmallSize(image, scaleRate);
}

/**
 * Scale images small size to a given width value.
 *
 * @param {Array<GalleryImage>} images
 * @param {number} width
 * @return {Array<GalleryImage>}
 */
export function scaleImagesSmallSizeToWidth(images:Array<GalleryImage>, width:number):Array<GalleryImage> {
    const scaleRate = sumImagesSmallSizeWidth(images) * 100 / width;
    const scaledGalleryImages = scaleImagesSmallSize(images, scaleRate);
    // Note: After scaling the images may be a situation when the scaled width
    // will be not equal to a given width. The following lines of code fix this issue.
    const diffWidth = width - sumImagesSmallSizeWidth(scaledGalleryImages);
    if (diffWidth) {
        const lastImageWidth = scaledGalleryImages[scaledGalleryImages.length - 1].getSmallSizeWidth() + diffWidth;
        scaledGalleryImages[scaledGalleryImages.length - 1].setSmallSizeWidth(lastImageWidth);
    }
    return scaledGalleryImages;
}

/**
 * Sum of the small size width of a given images.
 *
 * @param {Array<GalleryImage>} images
 * @return {number}
 */
export function sumImagesSmallSizeWidth(images:Array<GalleryImage>):number {
    return sum(images.map((image:GalleryImage) => image.getSmallSizeWidth()));
}
