import {Title} from '@angular/platform-browser';
import {Injectable, Inject} from '@angular/core';
import {EnvService} from '../env';

@Injectable()
export class TitleService {
    private pathSeparator = ' / ';

    constructor(@Inject(EnvService) private env:EnvService,
                @Inject(Title) private title:Title) {
    }

    setTitle = (newTitle:any):void => {
        this.title.setTitle(this.buildTitle(newTitle));
    };

    getTitle = ():string => {
        return this.title.getTitle();
    };

    private buildTitle = (newTitle:any):string => {
        let titlePieces = [this.env.get('appName')];

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
