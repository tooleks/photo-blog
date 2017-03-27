import {PublishedPhoto} from '../../../shared/models';
import {GalleryItem} from '../../../shared/components/gallery';

export class PhotoToGalleryItemMapper {

    static map(photo:PublishedPhoto):GalleryItem {
        let galleryItem = new GalleryItem;
        galleryItem.setId(photo.id);
        galleryItem.setFullSizeUrl(photo.absolute_url);
        galleryItem.setSmallSizeUrl(photo.thumbnails.medium.absolute_url);
        galleryItem.setSmallSizeHeight(photo.thumbnails.medium.height);
        galleryItem.setSmallSizeWidth(photo.thumbnails.medium.width);
        galleryItem.setLargeSizeUrl(photo.thumbnails.large.absolute_url);
        galleryItem.setLargeSizeHeight(photo.thumbnails.large.height);
        galleryItem.setLargeSizeWidth(photo.thumbnails.large.width);
        galleryItem.setAvgColor(photo.avg_color);
        galleryItem.setDescription(photo.description);
        galleryItem.setExif(String(photo.exif));
        return galleryItem;
    }
}
