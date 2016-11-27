import {Title} from '@angular/platform-browser';
import {Injectable, Inject} from '@angular/core';
import {EnvService} from '../env';

@Injectable()
export class TitleService {
    protected pathSeparator = ' / ';

    constructor(@Inject(EnvService) protected envService:EnvService,
                @Inject(Title) protected title:Title) {
    }

    setTitle(newTitle:any):void {
        this.title.setTitle(this.buildTitle(newTitle));
    }

    getTitle():string {
        return this.title.getTitle();
    }

    protected buildTitle(newTitle:any) {
        let titlePieces = [this.envService.get('appName')];

        if (!newTitle) {
            return titlePieces.reverse().join(this.pathSeparator);
        }

        if (newTitle instanceof Array) {
            titlePieces = titlePieces.concat(newTitle);
        } else {
            titlePieces.push(newTitle);
        }

        return titlePieces.reverse().join(this.pathSeparator);
    }
}
