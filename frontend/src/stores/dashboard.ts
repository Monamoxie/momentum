import { defineStore } from "pinia";
import axios from "@/axios";
import { type AxiosPromise } from "axios";
import type {
  ApiResponse,
  CreateTaskPayload,
  UpdateTaskPayload,
} from "@/types/api";

export const useDashboardStore = defineStore("dashboard", {
  state: () => ({
    token: localStorage.getItem("token") || "",
  }),
  actions: {
    get(): AxiosPromise<ApiResponse> {
      return axios.get("/tasks", {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        withCredentials: true,
      });
    },
    post(payload: CreateTaskPayload): AxiosPromise<ApiResponse> {
      return axios.post("/tasks", payload, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        withCredentials: true,
      });
    },
    update(payload: UpdateTaskPayload): AxiosPromise<ApiResponse> {
      return axios.patch("/tasks/" + payload.id, payload, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        withCredentials: true,
      });
    },
    delete(id: number): AxiosPromise<ApiResponse> {
      return axios.delete("/tasks/" + id, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        withCredentials: true,
      });
    },
  },
});
