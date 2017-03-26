import {Photo} from '../../../shared/models';
import {PublishedPhoto} from '../../../shared/models';
import {ExifMapper} from './exif-mapper';
import {TagMapper} from './tag-mapper';
import {ThumbnailMapper} from './thumbnail-mapper';

export class PhotoMapper {

    static mapToPublishedPhoto(item:any):PublishedPhoto {
        let publishedPhoto = new PublishedPhoto;
        publishedPhoto.id = item.id;
        publishedPhoto.created_by_user_id = item.created_by_user_id;
        publishedPhoto.absolute_url = item.absolute_url;
        publishedPhoto.avg_color = item.avg_color;
        publishedPhoto.description = item.description;
        publishedPhoto.created_at = item.created_at;
        publishedPhoto.updated_at = item.updated_at;
        publishedPhoto.exif = ExifMapper.map(item.exif);
        publishedPhoto.thumbnails.large = (ThumbnailMapper.map(item.thumbnails.large));
        publishedPhoto.thumbnails.medium = (ThumbnailMapper.map(item.thumbnails.medium));
        publishedPhoto.tags = item.tags.map(TagMapper.map);
        return publishedPhoto;
    }

    static mapToPhoto(item:any):PublishedPhoto {
        let photo = new Photo;
        photo.id = item.id;
        photo.photo_id = item.photo_id;
        photo.created_by_user_id = item.created_by_user_id;
        photo.absolute_url = item.absolute_url;
        photo.avg_color = item.avg_color;
        photo.description = item.description;
        photo.created_at = item.created_at;
        photo.updated_at = item.updated_at;
        photo.exif = ExifMapper.map(item.exif);
        photo.thumbnails.large = (ThumbnailMapper.map(item.thumbnails.large));
        photo.thumbnails.medium = (ThumbnailMapper.map(item.thumbnails.medium));
        photo.tags = item.tags.map(TagMapper.map);
        return photo;
    }

}
