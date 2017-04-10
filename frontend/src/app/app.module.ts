import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';
import {TransferHttpModule} from '../modules/transfer-http/transfer-http.module';
import {GoTopButtonModule} from 'ng2-go-top-button';
import {
    AboutMeComponent,
    AppComponent,
    ContactMeFormComponent,
    PageNotFoundComponent,
    SideBarComponent,
    TopBarComponent,
    SignInFormComponent,
    SignOutComponent,
    SubscriptionFormComponent,
    UnsubscriptionComponent,
} from './components';
import {HttpModule, JsonpModule} from '@angular/http';
import {AppRouting, AppRoutingProviders} from './app.routing';
import {SharedModule} from '../shared/shared.module';
import {PhotosModule} from '../photos/photos.module';
import {NoticesModule} from '../lib/notices';

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
