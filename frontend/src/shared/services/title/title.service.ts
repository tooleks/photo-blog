import {Injectable} from '@angular/core';
import {Title} from '@angular/platform-browser';

@Injectable()
export class TitleService {
    constructor(protected title:Title, protected defaultSegment:string = null, protected segmentsSeparator:string = ' / ') {
    }

    setTitle = (newTitle:any = []):void => {
        const title = this.buildTitle(newTitle);
        this.title.setTitle(title);
    };

    getTitle = ():string => {
        return this.title.getTitle();
    };

    getPageName = ():string => {
        return this.getTitle().split(this.segmentsSeparator)[0];
    };

    protected buildTitle = (segments:any = []):string => {
        let titleSegments = this.defaultSegment ? [this.defaultSegment] : [];
        if (segments instanceof Array) {
            titleSegments = titleSegments.concat(segments);
        } else {
            titleSegments.push(segments);
        }
        return titleSegments.reverse().join(this.segmentsSeparator);
    };
}
