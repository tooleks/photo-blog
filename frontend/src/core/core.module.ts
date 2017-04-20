import {NgModule} from '@angular/core';
import {DetectorModule} from './detector';
import {EnvModule} from './env';
import {ExternalModule} from './external';
import {HtmlModule} from './html';
import {SeoModule} from './seo';

@NgModule({
    imports: [
        DetectorModule,
        EnvModule,
        ExternalModule,
        HtmlModule,
        SeoModule,
    ],
    exports: [
        DetectorModule,
        EnvModule,
        ExternalModule,
        HtmlModule,
        SeoModule,
    ],
})
export class CoreModule {
}
