import Vue from "vue";
import ImageMarker from "./image-marker";

const ImageMarkerComponent = Vue.extend(ImageMarker);

export function provideImageMarkerHtml(propsData = {}) {
    const marker = new ImageMarkerComponent({propsData});
    return marker.$mount().$el.outerHTML;
}
