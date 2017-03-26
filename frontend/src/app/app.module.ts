import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule, JsonpModule} from '@angular/http';
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
    SubscriptionComponent,
} from './components';
import {AppRouting, AppRoutingProviders} from './app.routing';
import {SharedModule} from '../shared/shared.module';
import {PhotosModule} from '../photos/photos.module';
import {NoticesModule} from '../common/notices';

@NgModule({
    imports: [
        BrowserModule,
        HttpModule,
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
        SubscriptionComponent,
    ],
    exports: [],
    providers: [
        AppRoutingProviders,
    ],
    bootstrap: [
        AppComponent,
    ],
})
export class AppModule {
}
