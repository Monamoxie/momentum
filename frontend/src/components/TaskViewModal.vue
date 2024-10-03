<template>
  <div class="modal" tabindex="-1" :id="modalId" :class="extraStylesheet">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header d-flex flex-wrap justify-content-between">
          <h3 class="modal-title text-gray-600">TASK DETAIL</h3>
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
            <template v-if="loadingTaskDetail">
              <p class="text-center">Loading...</p>
            </template>
            <template v-else>
              <div class="form-group row">
                <h6 class="col-sm-3 col-form-label">Name</h6>
                <div class="col-sm-9">
                  <div class="d-flex flex-column">{{ name }}</div>
                </div>
              </div>

              <div class="form-group row mt-3">
                <h6 class="col-sm-3 col-form-label">Label</h6>
                <div class="col-sm-9">
                  <div class="d-flex flex-column">
                    {{ label }}
                  </div>
                </div>
              </div>

              <div class="form-group row mt-3">
                <h6 class="col-sm-3 col-form-label">Priority</h6>
                <div class="col-sm-9">
                  <div class="d-flex flex-column">{{ priority }}</div>
                </div>
              </div>

              <div class="form-group row mt-3">
                <h6 class="col-sm-3 col-form-label">Status</h6>
                <div class="col-sm-9">
                  <div class="d-flex flex-column">
                    {{ status }}
                  </div>
                </div>
              </div>

              <div class="form-group row mt-3">
                <h6 class="col-sm-3 col-form-label">Note</h6>
                <div class="col-sm-9">
                  <div class="d-flex flex-column">
                    {{ note }}
                  </div>
                </div>
              </div>

              <div class="form-group row mt-4 mb-3">
                <div class="col-sm-10">
                  <button @click="addSubTask" class="btn btn-success theme1">
                    Add Sub Task
                  </button>
                </div>
              </div>

              <h2 class="pt-5">SUB TASKS</h2>
              <template v-if="hasChildren">
                <table class="table table-bordered">
                  <tr>
                    <th>Name</th>
                    <th>Label</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Note</th>
                    <th>View</th>
                    <th>Edit</th>
                    <th>Delete</th>
                  </tr>
                  <tr v-for="(child, index) in task?.children" :key="index">
                    <td>{{ child.name }}</td>
                    <td>{{ child.label }}</td>
                    <td>{{ child.priority }}</td>
                    <td>{{ child.status }}</td>
                    <td>{{ child.note }}</td>
                    <td>
                      <button
                        @click="viewSubTask(child)"
                        class="btn btn-primary btn-sm"
                      >
                        View
                      </button>
                    </td>
                    <td>
                      <button
                        @click="editSubTask(child)"
                        class="btn btn-sm btn-primary theme1 text-white"
                      >
                        Edit
                      </button>
                    </td>
                    <td>
                      <button
                        @click="deleteSubTask(child)"
                        class="btn btn-danger btn-sm"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </table>
              </template>
              <template v-else>
                <div class="alert alert-warning">No sub task</div>
              </template>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent, type PropType } from "vue";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import { Modal } from "bootstrap";
import type { Task } from "@/types/api";

export default defineComponent({
  name: "TaskViewModal",
  components: { ErrorDisplayBoard },
  data() {
    return {
      id: this.task?.id,
      name: this.task?.name || "",
      label: this.task?.label || "",
      priority: this.task?.priority || "low",
      status: this.task?.status || "pending",
      note: this.task?.note || "",
      errorResponse: [] as string[] | string,
      modal: null as Modal | null,
    };
  },
  emits: [
    "modalClosed",
    "createSubTask",
    "viewSubTask",
    "editSubTask",
    "deleteSubTask",
  ],
  props: {
    task: {
      type: Object as PropType<Task | null>,
      required: false,
    },
    loadingTaskDetail: {
      type: Boolean,
    },
  },
  watch: {
    task: {
      immediate: true,
      handler(newTask) {
        if (newTask) {
          this.id = newTask.id;
          this.name = newTask.name || "";
          this.label = newTask.label || "";
          this.priority = newTask.priority || "low";
          this.status = newTask.status || "pending";
          this.note = newTask.note || "";
        }
      },
    },
  },
  mounted() {
    const modalElem = document.getElementById(this.modalId) as HTMLElement;
    this.modal = new Modal(modalElem);

    this.modal.show();

    modalElem.addEventListener("hidden.bs.modal", (event) => {
      if (this.modal) {
        this.modal.hide();

        this.$emit("modalClosed");
      }
    });
  },
  computed: {
    modalId(): string {
      if (this.task) {
        return "view-modal-" + this.task.id;
      }
      return "viewer";
    },
    hasChildren(): boolean {
      return (this.task?.children && this.task.children.length > 0) || false;
    },
    extraStylesheet(): string {
      if (this.hasChildren) return "modal-xl";
      else return "";
    },
  },
  methods: {
    addSubTask() {
      if (this.modal) {
        this.modal.hide();
        this.$emit("modalClosed");
        this.$emit("createSubTask");
      }
    },
    viewSubTask(data: Task) {
      if (this.modal) {
        this.$emit("viewSubTask", data);
      }
    },
    editSubTask(data: Task) {
      if (this.modal) {
        this.modal.hide();
        this.$emit("modalClosed");
        this.$emit("editSubTask", data);
      }
    },
    deleteSubTask(data: Task) {
      if (this.modal) {
        this.$emit("deleteSubTask", data);
      }
    },
  },
});
</script>
