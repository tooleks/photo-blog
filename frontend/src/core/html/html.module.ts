import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {TagInputModule} from 'ng2-tag-input';
import {FileSelectInputComponent, TagsSelectInputComponent} from './components';
import {SafeHtmlPipe} from './pipes';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        TagInputModule,
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
