import {Injectable} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Injectable()
export class TitleService {
    protected titleSegments:Array<string> = [];

    constructor(protected title:Title, protected defaultSegment:string = null, protected segmentsSeparator:string = ' / ') {
        this.setTitle();
    }

    renderTitle = ():void => {
        const titleSegments:Array<string> = [].concat(this.titleSegments);
        const title:string = titleSegments.reverse().join(this.segmentsSeparator);
        this.title.setTitle(title);
    };

    setTitle = (title:any = []):void => {
        title instanceof Array ? this.setTitleSegments(title) : this.setTitleSegments([title]);
    };

    getTitle = ():string => {
        return this.title.getTitle();
    };

    getPageName = ():string => {
        return this.titleSegments[this.titleSegments.length - 1];
    };

    setTitleSegments = (segments:Array<string> = []):void => {
        this.titleSegments = this.defaultSegment ? [this.defaultSegment] : [];
        this.titleSegments = this.titleSegments.concat(segments);
        this.renderTitle();
    };

    pushTitleSegment = (segment:string):void => {
        this.titleSegments.push(segment);
        this.renderTitle();
    };

    popTitleSegment = ():void => {
        if (this.titleSegments.length > 1) {
            this.titleSegments.pop();
            this.renderTitle();
        }
    };
}
