import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {PhotoFormComponent, PhotosComponent, PhotosByTagComponent, PhotosBySearchPhraseComponent} from './components';

const PhotosRoutes:Routes = [
    {
        path: 'photo/add',
        component: PhotoFormComponent,
    },
    {
        path: 'photo/edit/:id',
        component: PhotoFormComponent,
    },
    {
        path: 'photos',
        component: PhotosComponent,
    },
    {
        path: 'photos/tag/:tag',
        component: PhotosByTagComponent,
    },
    {
        path: 'photos/search',
        component: PhotosBySearchPhraseComponent,
    },
];

export const PhotosRoutingProviders:any[] = [];

export const PhotosRouting:ModuleWithProviders = RouterModule.forRoot(PhotosRoutes);
