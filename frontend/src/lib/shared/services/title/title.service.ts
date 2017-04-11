import {Title} from '@angular/platform-browser';
import {Injectable} from '@angular/core';
import {AppService} from '../app';

@Injectable()
export class TitleService {
    protected pathSeparator = ' / ';

    constructor(protected app:AppService,
                protected title:Title) {
    }

    setTitle = (newTitle?:any):void => {
        newTitle = newTitle || [];
        this.title.setTitle(this.buildTitle(newTitle));
    };

    getTitle = ():string => {
        return this.title.getTitle();
    };

    getPageName = ():string => {
        return this.getTitle().split(this.pathSeparator)[0];
    };

    protected buildTitle = (newTitle:any):string => {
        let titlePieces = [this.app.getName()];

        if (!newTitle) {
            return titlePieces.reverse().join(this.pathSeparator);
        }

        if (newTitle instanceof Array) {
            titlePieces = titlePieces.concat(newTitle);
        } else {
            titlePieces.push(newTitle);
        }

        return titlePieces.reverse().join(this.pathSeparator);
    };
}
