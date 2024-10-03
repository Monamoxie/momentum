<template>
  <div class="modal" tabindex="-1" :id="modalId">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header d-flex flex-wrap justify-content-between">
          <h3 class="modal-title text-gray-600">{{ title }}</h3>
          <span
            aria-hidden="true"
            class="modal-close-icon cursor-p"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <i class="fa fa-times text-danger fs"></i>
          </span>
        </div>
        <div class="modal-body">
          <div class="container">
            <ErrorDisplayBoard
              v-if="errorResponse.length > 0"
              :server-response="errorResponse"
            ></ErrorDisplayBoard>
            <div class="alert alert-success" v-if="successMessage">
              {{ successMessage }}
            </div>
            <VeeForm
              v-slot="{ handleSubmit }"
              :validation-schema="validationSchema"
              as="div"
            >
              <form @submit="handleSubmit($event, emitData)">
                <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label"
                    >Name <small class="text-danger">*</small></label
                  >
                  <div class="col-sm-9">
                    <div class="d-flex flex-column">
                      <Field
                        name="name"
                        type="text"
                        id="name"
                        autocomplete="false"
                        class="form-control"
                        placeholder="Enter a name"
                        v-model="name"
                        required
                      />
                      <VeeErrorMessage
                        name="name"
                        class="alert alert-danger mt-2"
                      />
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3">
                  <label
                    for="academic-session-code"
                    class="col-sm-3 col-form-label"
                    >Label
                  </label>
                  <div class="col-sm-9">
                    <div class="d-flex flex-column">
                      <Field
                        type="text"
                        id="label"
                        name="label"
                        class="form-control"
                        placeholder="Enter label"
                        v-model="label"
                      />

                      <VeeErrorMessage
                        name="label"
                        class="alert alert-danger mt-2"
                      />
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3">
                  <label for="is-active" class="col-sm-3 col-form-label"
                    >Priority
                  </label>
                  <div class="col-sm-9">
                    <div class="d-flex flex-column">
                      <Field
                        as="select"
                        id="priority"
                        name="priority"
                        class="form-control"
                        v-model="priority"
                      >
                        <template v-if="taskPriorities">
                          <option
                            v-for="priority in taskPriorities"
                            :value="priority"
                          >
                            {{ capitalizeFirstLetter(priority) }}
                          </option>
                        </template>
                      </Field>

                      <VeeErrorMessage
                        name="priority"
                        class="alert alert-danger mt-2"
                      />
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3">
                  <label for="status" class="col-sm-3 col-form-label"
                    >Status
                  </label>
                  <div class="col-sm-9">
                    <div class="d-flex flex-column">
                      <Field
                        as="select"
                        id="status"
                        name="status"
                        class="form-control"
                        v-model="status"
                      >
                        <template v-if="taskStatuses">
                          <option
                            v-for="status in taskStatuses"
                            :value="status"
                          >
                            {{ capitalizeFirstLetter(status) }}
                          </option>
                        </template>
                      </Field>

                      <VeeErrorMessage
                        name="status"
                        class="alert alert-danger mt-2"
                      />
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-3">
                  <label
                    for="academic-session-code"
                    class="col-sm-3 col-form-label"
                    >Note
                  </label>
                  <div class="col-sm-9">
                    <div class="d-flex flex-column">
                      <Field
                        as="textarea"
                        type="text"
                        id="note"
                        name="note"
                        class="form-control"
                        v-model="note"
                      />

                      <VeeErrorMessage
                        name="note"
                        class="alert alert-danger mt-2"
                      />
                    </div>
                  </div>
                </div>

                <div class="form-group row mt-4 mb-3">
                  <div class="col-sm-10">
                    <button
                      type="submit"
                      class="btn btn-success theme1"
                      :disabled="processing"
                    >
                      <template v-if="processing">Please wait...</template>
                      <template v-else>
                        {{
                          actionType == "create" ? "Create" : "Update"
                        }}</template
                      >
                    </button>
                  </div>
                </div>
              </form>
            </VeeForm>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, type PropType } from "vue";
import * as yup from "yup";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import { Modal } from "bootstrap";
import type { CreateTaskPayload, Task, UpdateTaskPayload } from "@/types/api";

export default defineComponent({
  name: "SubTaskFormModal",
  components: { ErrorDisplayBoard },
  data() {
    return {
      parent_id: "",
      parent_name: "",
      name: this.task?.name || "",
      label: this.task?.label || "",
      priority: this.task?.priority || "low",
      status: this.task?.status || "pending",
      note: this.task?.note || "",
      errorResponse: [] as string[] | string,
    };
  },
  emits: ["createTask", "updateTask", "modalClosed"],
  props: {
    task: {
      type: Object as PropType<Task | null>,
      required: true,
      default: null,
    },
    taskStatuses: {
      type: Array<string>,
      required: true,
    },
    taskPriorities: {
      type: Array<string>,
      required: true,
    },
    actionType: {
      type: String,
      required: true,
    },
    successMessage: {
      type: String,
    },
    errorMessage: {
      type: String,
    },
    processing: {
      type: Boolean,
    },
  },
  mounted() {
    const modalElem = document.getElementById(this.modalId) as HTMLElement;
    const modal = new Modal(modalElem);

    modal.show();

    modalElem.addEventListener("hidden.bs.modal", (event) => {
      modal.hide();

      this.$emit("modalClosed");
    });

    console.log("Moounting sub taks");
  },
  computed: {
    validationSchema() {
      return yup.object({
        name: yup.string().required(),
      });
    },
    modalId(): string {
      if (this.task) {
        return "modal-" + this.task.id;
      }
      return "modal";
    },
    title(): string {
      return this.actionType == "create" ? "ADD SUB TASK" : "EDIT SUB TASK";
    },
  },
  methods: {
    emitData() {
      if (this.actionType == "create") {
        return this.emitCreateTask();
      }

      return this.emitUpdateTask();
    },
    emitCreateTask() {
      const payload: CreateTaskPayload = {
        name: this.name,
        label: this.label,
        priority: this.priority,
        note: this.note,
        status: this.status,
      };

      this.$emit("createTask", payload);
    },
    emitUpdateTask() {
      const payload: UpdateTaskPayload = {
        name: this.name,
        label: this.label,
        priority: this.priority,
        note: this.note,
        status: this.status,
        id: this.task?.id,
      };

      this.$emit("updateTask", payload);
    },
    capitalizeFirstLetter(text: string) {
      return text.charAt(0).toUpperCase() + text.slice(1);
    },
  },
});
</script>
