import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {HttpModule, JsonpModule} from '@angular/http';
import {
    AppComponent,
    SideBarComponent,
    TopBarComponent,
    PageNotFoundComponent,
    SignInFormComponent,
    SignOutComponent
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
        AppRouting,
        SharedModule,
        PhotosModule,
        NoticesModule,
    ],
    declarations: [
        AppComponent,
        SideBarComponent,
        TopBarComponent,
        PageNotFoundComponent,
        SignInFormComponent,
        SignOutComponent,
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
