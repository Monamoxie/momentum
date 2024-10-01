<template>
  <div class="modal" tabindex="-1" :id="modalId">
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
                <button
                  aria-hidden="true"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                  class="modal-close-icon btn btn-success theme1"
                >
                  Close
                </button>
              </div>
            </div>
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
      name: this.task?.name || "",
      label: this.task?.label || "",
      priority: this.task?.priority || "low",
      status: this.task?.status || "pending",
      note: this.task?.note || "",
      errorResponse: [] as string[] | string,
    };
  },
  emits: ["modalClosed"],
  props: {
    task: {
      type: Object as PropType<Task>,
      required: false,
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
  },
  computed: {
    modalId(): string {
      if (this.task) {
        return "view-modal-" + this.task.id;
      }
      return "viewer";
    },
  },
});
</script>
