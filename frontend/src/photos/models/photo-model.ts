import {TagModel} from './tag-model';
import {ThumbnailModel} from './thumbnail-model';

export class PhotoModel {
    id:number;
    description:string;
    relative_url:string;
    created_at:string;
    updated_at:string;
    is_uploaded:boolean;
    is_published:boolean;
    absolute_url:string;
    tags:TagModel[] = [];
    thumbnails:ThumbnailModel[] = [];
}
