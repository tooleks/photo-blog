import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormsModule} from '@angular/forms';
import {NoticesComponent} from './components';
import {NoticesService} from './services';

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
    ],
    declarations: [
        NoticesComponent,
    ],
    exports: [
        NoticesComponent,
    ],
    providers: [
        NoticesService,
    ],
})
export class NoticesModule {
}
