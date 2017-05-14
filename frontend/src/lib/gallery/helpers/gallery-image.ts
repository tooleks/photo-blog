import {GalleryImage} from '../models';
import {scale, sum} from './value';

export function scaleImageGridSize(image:GalleryImage, scaleRate:number):GalleryImage {
    let gridImageScaledSizeWidth = scale(image.getGridSizeWidth(), scaleRate);
    let gridImageScaledSizeHeight = scale(image.getGridSizeHeight(), scaleRate);

    return image
        .clone()
        .setGridSizeWidth(Math.floor(gridImageScaledSizeWidth))
        .setGridSizeHeight(Math.floor(gridImageScaledSizeHeight));
}

export function scaleImagesGridSize(images:Array<GalleryImage>, scaleRate:number):Array<GalleryImage> {
    return images.map((image:GalleryImage) => scaleImageGridSize(image, scaleRate));
}

export function scaleImageGridSizeToHeight(image:GalleryImage, height:number):GalleryImage {
    const scaleRate = image.getGridSizeHeight() * 100 / height;
    return scaleImageGridSize(image, scaleRate);
}

export function scaleImagesGridSizeToWidth(images:Array<GalleryImage>, width:number):Array<GalleryImage> {
    const scaleRate = sumImagesGridSizeWidth(images) * 100 / width;
    const scaledImages = scaleImagesGridSize(images, scaleRate);

    // Note: After scaling the images may be a situation when the scaled width will be not equal to a given width.
    const diffWidth = width - sumImagesGridSizeWidth(scaledImages);
    const lastImageWidth = diffWidth
        ? scaledImages[scaledImages.length - 1].getGridSizeWidth() + diffWidth
        : scaledImages[scaledImages.length - 1].getGridSizeWidth();
    // The "- 1" fixes the 'on scale' browser event issue when the rendered width value is greater than calculated.
    scaledImages[scaledImages.length - 1].setGridSizeWidth(lastImageWidth - 1);

    return scaledImages;
}

export function sumImagesGridSizeWidth(images:Array<GalleryImage>):number {
    return sum(images.map((image:GalleryImage) => image.getGridSizeWidth()));
}
