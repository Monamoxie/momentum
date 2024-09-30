import { defineStore } from "pinia";
import axios from "@/axios";
import { type AxiosPromise } from "axios";
import type { ApiResponse, SigninPayload } from "@/types/api";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    token: localStorage.getItem("token") || "",
  }),
  actions: {
    async signup(credentials: {
      email: string;
      password: string;
      password_confirmation: string;
    }) {
      const response = await axios.post("/signup", credentials);
      // this.token = response.data.token;
      // localStorage.setItem("token", this.token);
    },
    signin(credentials: SigninPayload): AxiosPromise<ApiResponse> {
      return axios
        .get("http://localhost:81/sanctum/csrf-cookie")
        .then((response) => {
          return axios.post("/signin", credentials, {
            withCredentials: true,
          });
        });
    },
    persistToken(token: string) {
      this.token = token;
      localStorage.setItem("token", token);
    },
    signout() {
      this.token = "";
      localStorage.removeItem("token");
    },
  },
});
