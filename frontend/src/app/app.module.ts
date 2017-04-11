import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {HttpModule, JsonpModule} from '@angular/http';
import {TransferHttpModule} from '../modules/transfer-http/transfer-http.module';
import {GoTopButtonModule} from 'ng2-go-top-button';
import {SharedModule} from '../shared';
import {PhotosModule} from '../photos';
import {NoticesModule} from '../lib';
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
        CommonModule,
        HttpModule,
        TransferHttpModule,
        JsonpModule,
        GoTopButtonModule,
        AppRouting,
        SharedModule,
        PhotosModule,
        NoticesModule,
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
    exports: [],
    providers: [
        AppRoutingProviders,
    ],
})
export class AppModule {
}
