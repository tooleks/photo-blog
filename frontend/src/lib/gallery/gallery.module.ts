import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {RouterModule} from '@angular/router';
import {GalleryComponent, GalleryViewerComponent, GalleryGridComponent} from './components'

@NgModule({
    imports: [
        CommonModule,
        RouterModule,
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
