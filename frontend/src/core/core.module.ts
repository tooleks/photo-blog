import {NgModule} from '@angular/core';
import {DetectorModule} from './detector';
import {ExternalModule} from './external';
import {HtmlModule} from './html';
import {SeoModule} from './seo';

@NgModule({
    imports: [
        DetectorModule,
        ExternalModule,
        HtmlModule,
        SeoModule,
    ],
    exports: [
        DetectorModule,
        ExternalModule,
        HtmlModule,
        SeoModule,
    ],
})
export class CoreModule {
}
