import { defineStore } from "pinia";
import axios from "@/axios";
import { type AxiosPromise } from "axios";
import type { ApiResponse, SigninPayload } from "@/types/api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: sessionStorage.getItem("token") || "",
  }),
  actions: {
    signup(credentials: {
      email: string;
      password: string;
      password_confirmation: string;
    }) {
      return axios.post("/signup", credentials, {
        withCredentials: true,
      });
    },
    signin(credentials: SigninPayload): AxiosPromise<ApiResponse> {
      return axios.post("/signin", credentials, {
        withCredentials: true,
      });
    },
    fetchCsrfToken() {
      return axios.get("http://localhost:81/sanctum/csrf-cookie", {
        withCredentials: true,
      });
    },
    persistToken(token: string) {
      this.token = token;
      sessionStorage.setItem("token", token);
    },
    signout() {
      this.token = "";
      sessionStorage.removeItem("token");
      localStorage.removeItem("token");
    },
    getToken(): string {
      return this.token;
    },
    isCsrfTokenSet(): Boolean {
      const csrfToken = this.getCookie("XSRF-TOKEN");
      return !!csrfToken;
    },
    getCookie(name: string) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop()?.split(";").shift();
    },
  },
});
