import "bootstrap/dist/css/bootstrap.min.css";

import "./assets/main.css";
import { createApp } from "vue";
import { createPinia } from "pinia";
import {
  Field as Field,
  Form as VeeForm,
  ErrorMessage as VeeErrorMessage,
} from "vee-validate";
import * as bootstrap from "bootstrap";
import App from "./App.vue";
import router from "./router";
import PrimeVue from "primevue/config";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import ColumnGroup from "primevue/columngroup";
import Row from "primevue/row";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import Paginator from "primevue/paginator";

window.bootstrap = bootstrap;

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.component("Field", Field);
app.component("VeeErrorMessage", VeeErrorMessage);
app.component("VeeForm", VeeForm);

app
  .use(PrimeVue, {
    unstyled: true,
    pt: {
      datatable: {
        table: {
          class: "table table-striped table-bordered primevue-datatable",
        },
      },
      paginator: {
        root: {
          class: "pagination",
        },
        firstpagebutton: { class: "btn btn-primary mr-1" },
        lastpagebutton: { class: "btn btn-primary mr-2" },
        previouspagebutton: { class: "btn btn-primary ml-1" },
        nextpagebutton: { class: "btn btn-primary mr-1" },
        pagebutton: { class: "btn btn-outline-primary m-1" },
        rowperpagedropdown: {
          class: "form-control",
        },
      },
    },
    ptOptions: {
      mergeSections: true,
      mergeProps: false,
    },
  })
  .component("DataTable", DataTable)
  .component("Column", Column)
  .component("ColumnGroup", ColumnGroup)
  .component("Row", Row)
  .component("Button", Button)
  .component("Dropdown", Dropdown)
  .component("Paginator", Paginator);

app.mount("#app");

// window.axios.defaults.headers.common["Authorization"] =
// "Bearer " + store.getters["auth/getAccessToken"];
