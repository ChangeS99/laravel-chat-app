import "./bootstrap";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from "ziggy";
import MainLayout from "./Layouts/MainLayout.vue";
import { IoPersonAddSharp, IoSearchSharp, MdMessage, IoAddCircleOutline, IoAdd, IoCloseCircleOutline } from "oh-vue-icons/icons";
import { OhVueIcon, addIcons } from "oh-vue-icons";

import "../css/app.css";


addIcons(IoPersonAddSharp, IoSearchSharp, MdMessage, IoAddCircleOutline, IoCloseCircleOutline);

createInertiaApp({
    progress: {
        includeCSS: true,
        showSpinner: true,
    },
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });

        // get the page
        let page = pages[`./Pages/${name}.vue`];

        // checks if the page has defined a layout property
        // by using export default { layout: Layout}
        // refer to any page in Pages directory
        // otherwise sets it to a default layout
        page.default.layout = page.default.layout || MainLayout;

        return pages[`./Pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component("v-icon", OhVueIcon)
            .mount(el)
;
    },
});
