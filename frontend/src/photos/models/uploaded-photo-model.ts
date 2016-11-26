import {ThumbnailModel} from './thumbnail-model';

export class UploadedPhotoModel {
    id:number;
    user_id:number;
    absolute_url:string;
    created_at:string;
    updated_at:string;
    thumbnails:ThumbnailModel[] = [];
}
