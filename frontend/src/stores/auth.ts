// stores/auth.ts
import { defineStore } from "pinia";
import axios from "@/axios";

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
            this.token = response.data.token;
            localStorage.setItem("token", this.token);
        },
        async signin(credentials: { email: string; password: string }) {
            return await axios.post("/signin", credentials);
            //     this.token = response.data.token;
            //     localStorage.setItem("token", this.token);
        },
        // logout() {
        //     this.token = "";
        //     localStorage.removeItem("token");
        // },
    },
});
