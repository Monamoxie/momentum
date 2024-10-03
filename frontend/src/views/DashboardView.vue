<template>
  <div class="subscribers-wrapper">
    <div class="container subcriba-bx-shadow1">
      <template v-if="loadingRecords">
        <p class="text-center">Loading ...</p>
      </template>
      <template v-else>
        <ErrorDisplayBoard
          v-if="errorResponse.length > 0"
          :server-response="errorResponse"
        ></ErrorDisplayBoard>
        <div class="alert alert-success" v-if="successResponse">
          {{ successResponse }}
        </div>
        <div class="d-flex flex-row-reverse">
          <button class="btn btn-primary theme1" @click="showCreateModal">
            Create New
          </button>
        </div>
        <DataTable
          v-model:filters="filters"
          :value="tasks"
          paginator
          :rows="pageRows"
          :loading="loadingRecords"
          dataKey="id"
          :globalFilterFields="['name', 'priority', 'label', 'status']"
        >
          <template #header>
            <div class="row">
              <div class="col-lg-4 col-sm-12">
                <div class="input-group mb-3">
                  <span class="input-group-text" id="datatable-filter"
                    ><i class="fa fa-search"></i
                  ></span>
                  <input
                    type="text"
                    class="form-control"
                    name="primevue_table_search_input"
                    placeholder="Search..."
                    v-model="filters['global'].value"
                    aria-describedby="datatable-filter"
                  />
                </div>
              </div>
            </div>
          </template>

          <Column field="name" header="Name" sortable></Column>
          <Column field="priority" header="Priority" sortable></Column>
          <Column field="status" header="Status" sortable></Column>
          <Column field="label" header="Label" sortable></Column>
          <Column header="View">
            <template #body="row">
              <button
                class="btn btn-primary btn-sm"
                @click="viewModal(row.data)"
              >
                View
              </button>
            </template>
          </Column>
          <Column header="Edit">
            <template #body="row">
              <button
                class="btn btn-sm btn-primary theme1"
                @click="showEditModal(row.data)"
              >
                Edit
              </button>
            </template>
          </Column>
          <Column header="Delete">
            <template #body="row">
              <button
                class="btn btn-danger btn-sm"
                @click="deleteTask(row.data.id)"
              >
                Delete
              </button>
            </template>
          </Column>
        </DataTable>
      </template>
    </div>
    <TaskFormModal
      :task="task"
      :modal-id="name"
      :action-type="actionType"
      :success-message="modalSuccessMessage"
      :error-message="modalErrorMessage"
      :processing="modalProcessing"
      :task-statuses="taskStatuses"
      :task-priorities="taskPriorties"
      :for-subtype="forSubType"
      @create-task="createTask"
      @update-task="updateTask"
      @modal-closed="showFormModal = false"
      v-if="showFormModal"
    />

    <TaskViewModal
      :task="task"
      :modal-id="name"
      :action-type="actionType"
      :loading-task-detail="loadingTaskDetail"
      @create-sub-task="showCreateSubTaskModal"
      @view-sub-task="showViewSubTask"
      @edit-sub-task="showEditSubTask"
      @delete-sub-task="showDeleteSubTask"
      @modal-closed="showViewModal = false"
      v-if="showViewModal"
    />
  </div>
</template>
<script lang="ts">
import { defineComponent, type PropType } from "vue";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import { useDashboardStore } from "../stores/dashboard";
import { mapActions } from "pinia";
import type { AxiosError, AxiosResponse } from "axios";
import type {
  ApiResponse,
  GetTaskResponse,
  CreateTaskPayload,
  TaskRowData,
  UpdateTaskPayload,
  GetSingleTaskResponse,
  Task,
} from "@/types/api";
import TaskFormModal from "@/components/TaskFormModal.vue";
import TaskViewModal from "@/components/TaskViewModal.vue";
import { FilterMatchMode } from "@primevue/core/api";

export default defineComponent({
  name: "DashboardView",
  data() {
    return {
      loadingRecords: true,
      dataLoaded: false,
      email: "",
      name: "",
      country: "",
      deleting: false,
      deleteErr: null,
      deleted: false,
      tasks: [] as Array<Task>,
      task: {} as Task | null,
      errorResponse: [] as string[] | string,
      successResponse: "",
      actionType: "create" as string,
      forSubType: false,
      showFormModal: false,
      showViewModal: false,
      modalSuccessMessage: "" as string,
      modalErrorMessage: "" as string,
      modalProcessing: false as boolean,
      taskStatuses: [] as string[],
      taskPriorties: [] as string[],
      pageRows: 15,
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      filters: {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      },

      subTaskActionType: "create" as string,
      showSubTaskFormModal: false,
      subTaskModalErrorMessage: "" as string,
      subTaskModalSuccessMessage: "" as string,
      subTaskModalProcessing: false as boolean,

      loadingTaskDetail: false,
    };
  },
  components: {
    ErrorDisplayBoard,
    TaskFormModal,
    TaskViewModal,
  },
  methods: {
    ...mapActions(useDashboardStore, [
      "get",
      "show",
      "post",
      "update",
      "delete",
    ]),
    getTasks() {
      this.get()
        .then((response: AxiosResponse<ApiResponse>) => {
          const responseData: GetTaskResponse = response.data.data;
          this.taskStatuses = responseData.task_statuses;
          this.taskPriorties = responseData.task_priorities;
          if (responseData.tasks.length < 1) {
            this.errorResponse = "No task";
          }

          this.tasks = responseData.tasks;
        })
        .catch((error: AxiosError<ApiResponse>) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.loadingRecords = false;
        });
    },
    createTask(payload: CreateTaskPayload) {
      this.modalSuccessMessage = "";
      this.post(payload)
        .then((response: AxiosResponse<ApiResponse>) => {
          const responseData: GetSingleTaskResponse = response.data.data;

          this.tasks.push(responseData.task);
          this.modalSuccessMessage = "New task has been added!";
        })
        .catch((error: AxiosError<ApiResponse>) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.loadingRecords = false;
        });
    },
    showCreateModal() {
      this.modalSuccessMessage = "";
      this.actionType = "create";
      this.showFormModal = true;
    },
    showEditModal(rowData: TaskRowData) {
      this.modalSuccessMessage = "";
      this.actionType = "edit";
      this.task = rowData;
      this.showFormModal = true;
    },
    viewModal(rowData: TaskRowData) {
      this.loadingTaskDetail = false;
      this.modalSuccessMessage = "";
      this.task = rowData;
      this.showViewModal = true;
    },
    clearFilter() {
      this.initFilters();
    },
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
    updateTask(payload: UpdateTaskPayload) {
      this.modalSuccessMessage = "";
      this.update(payload)
        .then(() => {
          this.tasks.push(payload);
          this.modalSuccessMessage = "Task has been successfully updated!";
        })
        .catch((error: AxiosError<ApiResponse>) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.loadingRecords = false;
        });
    },
    deleteTask(id: number) {
      const conf = confirm("Are you sure you wish to delete this entry?");
      this.delete(id)
        .then(() => {
          this.successResponse = "Task has been successfully deleted!";
          this.tasks.filter((task, index) => {
            if (task.id == id) {
              return this.tasks.splice(index, 1);
            }
          });
        })
        .catch((error: AxiosError<ApiResponse>) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.loadingRecords = false;
        });
    },
    initSubType() {
      [this.showViewModal, this.showFormModal, this.forSubType] = [
        false,
        true,
        true,
      ];
    },
    showCreateSubTaskModal(payload: Task) {
      this.initSubType();
    },
    showEditSubTask(payload: Task) {
      this.initSubType();

      this.actionType = "edit";
      this.task = payload;
    },
    showViewSubTask(payload: Task) {
      this.modalSuccessMessage = "";
      this.task = payload;
      this.showViewModal = true;
    },

    showDeleteSubTask(payload: Task) {
      this.deleteTask(payload.id);
    },
  },
  mounted() {
    this.getTasks();
    this.initFilters();
  },
});
</script>
