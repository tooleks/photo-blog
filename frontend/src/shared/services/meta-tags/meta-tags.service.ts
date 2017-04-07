import {Injectable} from '@angular/core';
import {Meta, MetaDefinition} from '@angular/platform-browser';

@Injectable()
export class MetaTagsService {
    constructor(private meta:Meta) {
        this.initDefaults();
    }

    private initDefaults = ():void => {
        const tags:Array<MetaDefinition> = [
            // General meta tags.
            {name: 'description', content: ''},
            // Open Graph related meta tags.
            {name: 'og:title', content: ''},
            {name: 'og:type', content: 'article'},
            {name: 'og:url', content: ''},
            {name: 'og:image', content: ''},
            {name: 'og:description', content: ''},
            {name: 'og:site_name', content: ''},
            // Twitter related meta tags.
            {name: 'twitter:card', content: 'summary'},
            {name: 'twitter:title', content: ''},
            {name: 'twitter:image', content: ''},
        ];
        this.meta.addTags(tags);
    };

    setUrl = (content:string):void => {
        this.meta.updateTag({name: 'og:url', content: content}, 'name="og:url"');
    };

    setTitle = (content:string):void => {
        this.meta.updateTag({name: 'og:title', content: content}, 'name="og:title"');
        this.meta.updateTag({name: 'og:site_name', content: content}, 'name="og:site_name"');
        this.meta.updateTag({name: 'twitter:title', content: content}, 'name="twitter:title"');
    };

    setDescription = (content:string):void => {
        this.meta.updateTag({name: 'description', content: content}, 'name="description"');
        this.meta.updateTag({name: 'og:description', content: content}, 'name="og:description"');
    };

    setImage = (content:string):void => {
        this.meta.updateTag({name: 'og:image', content: content}, 'name="og:image"');
        this.meta.updateTag({name: 'twitter:image', content: content}, 'name="twitter:image"');
    };
}
