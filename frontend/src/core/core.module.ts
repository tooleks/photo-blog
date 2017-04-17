import {NgModule} from '@angular/core';
import {EnvModule} from './env';
import {HtmlModule} from './html';
import {SeoModule} from './seo';

@NgModule({
    imports: [
        EnvModule,
        HtmlModule,
        SeoModule,
    ],
    exports: [
        EnvModule,
        HtmlModule,
        SeoModule,
    ],
})
export class CoreModule {
}
