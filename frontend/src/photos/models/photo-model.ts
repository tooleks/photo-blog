import {TagModel} from './tag-model';
import {ThumbnailModel} from './thumbnail-model';

export class PhotoModel {
    id:number;
    description:string;
    relative_url:string;
    is_draft:boolean;
    created_at:string;
    updated_at:string;
    is_uploaded:boolean;
    absolute_url:string;
    short_description:string;
    tags:TagModel[] = [];
    thumbnails:ThumbnailModel[] = [];
}
