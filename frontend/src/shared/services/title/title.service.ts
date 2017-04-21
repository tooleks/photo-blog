import {Injectable} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Injectable()
export class TitleService {
    protected segments:Array<string> = [];

    constructor(protected title:Title, protected defaultSegment:string = null, protected segmentsSeparator:string = ' / ') {
        this.setTitle();
    }

    setTitle = (title:any = []):void => {
        const segments:Array<string> = this.prepareSegments(title);
        this.setSegments(segments);
        this.renderSegments(this.segments);
    };

    getTitle = ():string => {
        return this.title.getTitle();
    };

    getPageName = ():string => {
        return this.segments.length ? this.segments[this.segments.length - 1] : '';
    };

    setDynamicTitle = (title:any = []):void => {
        const segments:Array<string> = this.prepareSegments(title);
        this.renderSegments([].concat(this.segments).concat(segments));
    };

    unsetDynamicTitle = ():void => {
        this.renderSegments(this.segments);
    };

    protected setSegments = (segments:Array<string>):void => {
        const defaultSegments:Array<string> = this.prepareSegments(this.defaultSegment);
        this.segments = [].concat(defaultSegments).concat(segments);
    };

    protected prepareSegments = (segments:any):Array<string> => {
        if (segments instanceof Array) {
            return [].concat(segments).map((segment:any) => String(segment));
        } else if (segments) {
            return [String(segments)];
        } else {
            return [];
        }
    };

    protected renderSegments = (segments:Array<string>):void => {
        const title:string = [].concat(segments).reverse().join(this.segmentsSeparator);
        this.title.setTitle(title);
    };
}
