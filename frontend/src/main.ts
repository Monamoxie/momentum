import "bootstrap/dist/css/bootstrap.min.css";
import "./assets/main.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import axios from "axios";

import App from "./App.vue";
import router from "./router";
window.axios = axios;
const app = createApp(App);

app.use(createPinia());
app.use(router);

app.mount("#app");

// window.axios.defaults.headers.common["Authorization"] =
// "Bearer " + store.getters["auth/getAccessToken"];
