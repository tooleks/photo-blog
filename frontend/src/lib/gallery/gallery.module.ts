import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {GalleryComponent, GalleryViewerComponent, GalleryGridComponent} from './components'

@NgModule({
    imports: [
        CommonModule,
    ],
    declarations: [
        GalleryComponent,
        GalleryViewerComponent,
        GalleryGridComponent,
    ],
    exports: [
        GalleryComponent,
        GalleryViewerComponent,
        GalleryGridComponent,
    ],
})
export class GalleryModule {
}
