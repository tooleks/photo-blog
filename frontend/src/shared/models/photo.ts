import {Tag} from './tag';
import {Thumbnail} from './thumbnail';

export class Photo {
    id:number;
    uploaded_photo_id:number;
    user_id:number;
    absolute_url:string;
    description:string;
    created_at:string;
    updated_at:string;
    thumbnails:Thumbnail[] = [];
    tags:Tag[] = [];

    setAttributes(attributes:any) {
        this.id = attributes.id;
        this.uploaded_photo_id = attributes.uploaded_photo_id;
        this.user_id = attributes.user_id;
        this.absolute_url = attributes.absolute_url;
        this.description = attributes.description;
        this.created_at = attributes.absolute_url;
        this.updated_at = attributes.absolute_url;
        this.thumbnails = attributes.thumbnails;
        this.tags = attributes.tags;
    }

    setUploadedAttributes(attributes:any) {
        this.uploaded_photo_id = attributes.id;
        this.user_id = attributes.user_id;
        this.absolute_url = attributes.absolute_url;
        this.created_at = attributes.absolute_url;
        this.updated_at = attributes.absolute_url;
        this.thumbnails = attributes.thumbnails;
    }
}
