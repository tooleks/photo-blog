import {Injectable} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Injectable()
export class TitleService {
    protected pageNameSegment:string = '';

    constructor(protected clientTitle:Title, protected defaultSegment:string = '', protected segmentsSeparator:string = ' | ') {
        this.init();
    }

    init():void {
        let newTitle:string = this.getPageNameSegment() + this.getSegmentsSeparator() + this.getDefaultSegment();
        this.clientTitle.setTitle(newTitle);
    }

    setDefaultSegment(newDefaultSegment:string):void {
        this.defaultSegment = newDefaultSegment;
        this.init();
    }

    getDefaultSegment():string {
        return this.defaultSegment;
    }

    setPageNameSegment(newPageNameSegment:string):void {
        this.pageNameSegment = newPageNameSegment;
        this.init();
    }

    getPageNameSegment():string {
        return this.pageNameSegment;
    }

    setSegmentsSeparator(newSegmentsSeparator:string):void {
        this.segmentsSeparator = newSegmentsSeparator;
        this.init();
    }

    getSegmentsSeparator():string {
        return this.segmentsSeparator;
    }

    getFullTitle():string {
        return this.clientTitle.getTitle();
    }
}
