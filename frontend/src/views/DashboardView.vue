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

        <div class="d-flex flex-row-reverse">
          <button class="btn btn-primary theme1" @click="showModal = true">
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
          :globalFilterFields="['name', 'code']"
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
          <Column field="label" header="Label" sortable></Column>
          <Column field="priority" header="Priority" sortable></Column>
          <Column field="status" header="Status" sortable></Column>
          <Column header="Edit">
            <template #body="row">
              <button class="btn btn-primary" @click="showEditModal(row.data)">
                Edit
              </button>
            </template>
          </Column>
          <Column header="Delete">
            <template #body="row">
              <button class="btn btn-danger">Delete</button>
            </template>
          </Column>
        </DataTable>
      </template>
    </div>
    <TaskFormModal
      :task="task"
      :modal-id="name"
      :action-type="actionType"
      :country="country"
      :success-message="modalSuccessMessage"
      :error-message="modalErrorMessage"
      :processing="modalProcessing"
      :task-statuses="taskStatuses"
      :task-priorities="taskPriorties"
      @create-task="createTask"
      @modal-closed="showModal = false"
      v-if="showModal"
    />
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import { useDashboardStore } from "../stores/dashboard";
import { mapActions } from "pinia";
import type { AxiosError, AxiosResponse } from "axios";
import type {
  ApiResponse,
  GetTaskResponse,
  CreateTaskPayload,
  TaskRowData,
} from "@/types/api";
import TaskFormModal from "@/components/TaskFormModal.vue";
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
      tasks: [] as Array<Object>,
      task: {},
      errorResponse: [] as string[] | string,
      actionType: "create" as string,
      showModal: false,
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
    };
  },
  components: {
    ErrorDisplayBoard,
    TaskFormModal,
  },
  methods: {
    ...mapActions(useDashboardStore, ["get", "post"]),
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
      this.post(payload)
        .then(() => {
          this.tasks.push(payload);
          this.showModal = false;
        })
        .catch((error: AxiosError<ApiResponse>) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.loadingRecords = false;
        });
    },
    showEditModal(rowData: TaskRowData) {
      this.task = rowData;
      this.showModal = true;
    },
    clearFilter() {
      this.initFilters();
    },
    initFilters() {
      this.filters = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS },
      };
    },
  },
  mounted() {
    this.getTasks();
    this.initFilters();
  },
});
</script>
