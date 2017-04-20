import {NgModule} from '@angular/core';
import {HttpModule, JsonpModule} from '@angular/http';
import {GoTopButtonModule} from 'ng2-go-top-button';
import {TransferHttpModule} from '../sys';
import {SharedModule} from '../shared';
import {PhotosModule} from '../photos';
import {AppRouting, AppRoutingProviders} from './app.routing';
import {
    AboutMeComponent,
    AppComponent,
    ContactMeFormComponent,
    PageNotFoundComponent,
    BottomBarComponent,
    SideBarComponent,
    TopBarComponent,
    SignInFormComponent,
    SignOutComponent,
    SubscriptionFormComponent,
    UnsubscriptionComponent,
} from './components';

@NgModule({
    imports: [
        HttpModule,
        JsonpModule,
        GoTopButtonModule,
        TransferHttpModule,
        AppRouting,
        SharedModule,
        PhotosModule,
    ],
    declarations: [
        AboutMeComponent,
        AppComponent,
        ContactMeFormComponent,
        PageNotFoundComponent,
        BottomBarComponent,
        SideBarComponent,
        TopBarComponent,
        SignInFormComponent,
        SignOutComponent,
        SubscriptionFormComponent,
        UnsubscriptionComponent,
    ],
    providers: [
        AppRoutingProviders,
    ],
})
export class AppModule {
}
