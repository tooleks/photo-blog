import {TagModel} from './tag-model';
import {ThumbnailModel} from './thumbnail-model';

export class PhotoModel {
    id:number;
    uploaded_photo_id:number;
    user_id:number;
    absolute_url:string;
    description:string;
    created_at:string;
    updated_at:string;
    thumbnails:ThumbnailModel[] = [];
    tags:TagModel[] = [];
}
