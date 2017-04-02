import {GalleryImage} from '../models';
import {scale, sum} from './value';

/**
 * Scale the image small size by a given scale rate.
 *
 * @param {GalleryImage} galleryImage
 * @param {number} scaleRate
 * @return {GalleryImage}
 */
export function scaleImageSmallSize(galleryImage:GalleryImage, scaleRate:number):GalleryImage {
    let smallImageScaledSizeWidth = scale(galleryImage.getSmallSizeWidth(), scaleRate);
    let smallImageScaledSizeHeight = scale(galleryImage.getSmallSizeHeight(), scaleRate);

    return galleryImage
        .clone()
        .setSmallSizeWidth(Math.floor(smallImageScaledSizeWidth))
        .setSmallSizeHeight(Math.floor(smallImageScaledSizeHeight));
}

/**
 * Scale images small size by a given scale rate.
 *
 * @param {Array<GalleryImage>} galleryImages
 * @param {number} scaleRate
 * @return {Array<GalleryImage>}
 */
export function scaleImagesSmallSize(galleryImages:Array<GalleryImage>, scaleRate:number):Array<GalleryImage> {
    return galleryImages.map((galleryImage:GalleryImage) => scaleImageSmallSize(galleryImage, scaleRate));
}

/**
 * Scale the image small size to a given height value.
 *
 * @param {GalleryImage} galleryImage
 * @param {number} height
 * @return {GalleryImage}
 */
export function scaleImageSmallSizeToHeight(galleryImage:GalleryImage, height:number):GalleryImage {
    const scaleRate = galleryImage.getSmallSizeHeight() * 100 / height;
    return scaleImageSmallSize(galleryImage, scaleRate);
}

/**
 * Scale images small size to a given width value.
 *
 * @param {Array<GalleryImage>} galleryImages
 * @param {number} width
 * @return {Array<GalleryImage>}
 */
export function scaleImagesSmallSizeToWidth(galleryImages:Array<GalleryImage>, width:number):Array<GalleryImage> {
    const scaleRate = sumImagesSmallSizeWidth(galleryImages) * 100 / width;
    const scaledGalleryImages = scaleImagesSmallSize(galleryImages, scaleRate);
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
 * @param {Array<GalleryImage>} galleryImages
 * @return {number}
 */
export function sumImagesSmallSizeWidth(galleryImages:Array<GalleryImage>):number {
    return sum(galleryImages.map((galleryImage:GalleryImage) => galleryImage.getSmallSizeWidth()));
}
