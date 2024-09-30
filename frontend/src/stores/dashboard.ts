import { defineStore } from "pinia";
import axios from "@/axios";
import { type AxiosPromise } from "axios";
import type {
  ApiResponse,
  CreateTaskPayload,
  SigninPayload,
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
      console.log(this.token);
      return axios.post("/tasks", payload, {
        headers: {
          Authorization: `Bearer ${this.token}`,
        },
        withCredentials: true,
      });
    },
  },
});
