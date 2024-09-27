import "./bootstrap";
window.Vue = require("vue").default;

import router from "./routes/router";
import Base from "./layouts/Base.vue";
import { store } from "./store/index";

const app = new Vue({
    el: "#app",
    router,
    store,
    render: (h) => h(Base),
});
