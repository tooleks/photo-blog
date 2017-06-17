import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {FileSelectInputComponent, TagsSelectInputComponent} from './components';
import {SafeHtmlPipe} from './pipes';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
    ],
    declarations: [
        FileSelectInputComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
    exports: [
        CommonModule,
        FormsModule,
        FileSelectInputComponent,
        TagsSelectInputComponent,
        SafeHtmlPipe,
    ],
})
export class HtmlModule {
}
