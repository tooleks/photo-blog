import Vue from "vue";
import {reverseGeocode} from "esri-leaflet-geocoder";
import LocationPopup from "./location-popup";

const LocationPopupComponent = Vue.extend(LocationPopup);

export function provideLocationPopupHtml({lat, lng} = {}) {
    return new Promise((resolve) => {
        reverseGeocode()
            .latlng([lat, lng])
            .run((error, result) => {
                let address;
                if (!error) {
                    address = result.address.LongLabel;
                }
                const propsData = {lat, lng, address};
                const popup = new LocationPopupComponent({propsData});
                const html = popup.$mount().$el.outerHTML;
                resolve(html);
            });
    });
}
