import {Injectable} from '@angular/core';
import {Meta, MetaDefinition} from '@angular/platform-browser';

@Injectable()
export class MetaTagsService {
    constructor(protected meta:Meta) {
        this.initDefaults();
    }

    protected initDefaults = ():void => {
        const tags:Array<MetaDefinition> = [
            // General meta tags.
            {name: 'description', content: ''},
            // Open Graph related meta tags.
            {property: 'og:title', content: ''},
            {property: 'og:type', content: 'article'},
            {property: 'og:url', content: ''},
            {property: 'og:image', content: ''},
            {property: 'og:description', content: ''},
            {property: 'og:site_name', content: ''},
            // Twitter related meta tags.
            {name: 'twitter:card', content: 'summary_large_image'},
            {name: 'twitter:title', content: ''},
            {name: 'twitter:image', content: ''},
        ];
        this.meta.addTags(tags);
    };

    setWebsiteName = (content:string):void => {
        this.meta.updateTag({property: 'og:site_name', content: content}, 'property="og:site_name"');
    };

    setUrl = (content:string):void => {
        this.meta.updateTag({property: 'og:url', content: content}, 'property="og:url"');
    };

    setTitle = (content:string):void => {
        this.meta.updateTag({property: 'og:title', content: content}, 'property="og:title"');
        this.meta.updateTag({name: 'twitter:title', content: content}, 'name="twitter:title"');
    };

    setDescription = (content:string):void => {
        this.meta.updateTag({name: 'description', content: content}, 'name="description"');
        this.meta.updateTag({property: 'og:description', content: content}, 'property="og:description"');
    };

    setImage = (content:string):void => {
        this.meta.updateTag({property: 'og:image', content: content}, 'property="og:image"');
        this.meta.updateTag({name: 'twitter:image', content: content}, 'name="twitter:image"');
    };
}
