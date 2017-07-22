import {ModuleWithProviders} from '@angular/core';
import {Routes, RouterModule} from '@angular/router';
import {
    AboutMeComponent,
    ContactMeFormComponent,
    LandingPageComponent,
    PageNotFoundComponent,
    SignInFormComponent,
    SignOutComponent,
    SubscriptionFormComponent,
    UnsubscriptionComponent,
} from './components';

const AppRoutes: Routes = [
    {
        path: '',
        redirectTo: '/home',
        pathMatch: 'full',
    },
    {
        path: 'signin',
        component: SignInFormComponent,
    },
    {
        path: 'signout',
        component: SignOutComponent,
    },
    {
        path: 'about-me',
        component: AboutMeComponent,
    },
    {
        path: 'contact-me',
        component: ContactMeFormComponent,
    },
    {
        path: 'home',
        component: LandingPageComponent,
    },
    {
        path: 'subscription',
        component: SubscriptionFormComponent,
    },
    {
        path: 'unsubscription/:token',
        component: UnsubscriptionComponent,
    },
    {
        path: '404',
        component: PageNotFoundComponent
    },
    {
        path: '**',
        component: PageNotFoundComponent
    }
];

export const AppRoutingProviders: any[] = [];

export const AppRouting: ModuleWithProviders = RouterModule.forRoot(AppRoutes);
