import {GalleryImage} from '../models';
import {scale, sum} from './value';

/**
 * Scale the image grid size by a given scale rate.
 *
 * @param {GalleryImage} image
 * @param {number} scaleRate
 * @return {GalleryImage}
 */
export function scaleImageGridSize(image:GalleryImage, scaleRate:number):GalleryImage {
    let gridImageScaledSizeWidth = scale(image.getGridSizeWidth(), scaleRate);
    let gridImageScaledSizeHeight = scale(image.getGridSizeHeight(), scaleRate);

    return image
        .clone()
        .setGridSizeWidth(Math.floor(gridImageScaledSizeWidth))
        .setGridSizeHeight(Math.floor(gridImageScaledSizeHeight));
}

/**
 * Scale images grid size by a given scale rate.
 *
 * @param {Array<GalleryImage>} images
 * @param {number} scaleRate
 * @return {Array<GalleryImage>}
 */
export function scaleImagesGridSize(images:Array<GalleryImage>, scaleRate:number):Array<GalleryImage> {
    return images.map((image:GalleryImage) => scaleImageGridSize(image, scaleRate));
}

/**
 * Scale the image grid size to a given height value.
 *
 * @param {GalleryImage} image
 * @param {number} height
 * @return {GalleryImage}
 */
export function scaleImageGridSizeToHeight(image:GalleryImage, height:number):GalleryImage {
    const scaleRate = image.getGridSizeHeight() * 100 / height;
    return scaleImageGridSize(image, scaleRate);
}

/**
 * Scale images grid size to a given width value.
 *
 * @param {Array<GalleryImage>} images
 * @param {number} width
 * @return {Array<GalleryImage>}
 */
export function scaleImagesGridSizeToWidth(images:Array<GalleryImage>, width:number):Array<GalleryImage> {
    const scaleRate = sumImagesGridSizeWidth(images) * 100 / width;
    const scaledImages = scaleImagesGridSize(images, scaleRate);
    // Note: After scaling the images may be a situation when the scaled width
    // will be not equal to a given width. The following lines of code fix this issue.
    const diffWidth = width - sumImagesGridSizeWidth(scaledImages);
    if (diffWidth) {
        const lastImageWidth = scaledImages[scaledImages.length - 1].getGridSizeWidth() + diffWidth;
        scaledImages[scaledImages.length - 1].setGridSizeWidth(lastImageWidth);
    }
    return scaledImages;
}

/**
 * Sum of the grid size width of a given images.
 *
 * @param {Array<GalleryImage>} images
 * @return {number}
 */
export function sumImagesGridSizeWidth(images:Array<GalleryImage>):number {
    return sum(images.map((image:GalleryImage) => image.getGridSizeWidth()));
}
