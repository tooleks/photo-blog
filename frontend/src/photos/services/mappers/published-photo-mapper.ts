import {PublishedPhoto} from '../../../shared/models';
import {ExifMapper} from './exif-mapper';
import {TagMapper} from './tag-mapper';
import {ThumbnailMapper} from './thumbnail-mapper';

export class PublishedPhotoMapper {

    static map(item:any):PublishedPhoto {
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

}
