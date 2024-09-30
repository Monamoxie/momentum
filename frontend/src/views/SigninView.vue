<template>
  <div class="home-wrapper container">
    <div class="row">
      <div class="col-md-6">
        <h1>
          Be part <strong>of</strong> something new
          <span class="dot">.</span>
        </h1>
        <div
          class="subscriba-form-wrapper subcriba-bx-shadow1"
          v-if="!subscribeSuccess"
        >
          <ErrorDisplayBoard
            v-if="errorResponse.length > 0"
            :server-response="errorResponse"
          ></ErrorDisplayBoard>
          <form @submit.prevent="process">
            <div class="form-group">
              <label for="email">Email</label>
              <input
                v-model="email"
                class="form-control"
                placeholder="Email address"
                required
                type="email"
              />
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input
                type="password"
                v-model="password"
                class="form-control"
                placeholder="Password"
                required
              />
            </div>

            <div class="form-group">
              <button
                class="btn btn-primary bubbly-button theme1"
                type="submit"
              >
                <span v-if="processing" class="loader-palette">
                  Loading...
                </span>
                <span v-else>Sign In</span>
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-6 side-hero">
        <img src="@/assets/images/hero.jpg" alt="Hero Image" />
      </div>
    </div>
  </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import { useAuthStore } from "../stores/auth";
import { mapActions } from "pinia";
import type { AxiosResponse } from "axios";
import type { ApiResponse, SignInSuccess } from "@/types/api";

export default defineComponent({
  name: "Home",
  data() {
    return {
      email: "test@test.com",
      password: "123456",
      processing: false,
      errorResponse: [] as string[] | string,
      subscribeSuccess: false,
    };
  },
  components: {
    ErrorDisplayBoard,
  },
  methods: {
    ...mapActions(useAuthStore, ["signin", "persistToken"]),
    process() {
      this.processing = true;
      this.errorResponse = "";

      const payload = {
        email: this.email,
        password: this.password,
      };

      this.signin(payload)
        .then((response: AxiosResponse<ApiResponse>) => {
          const responseData: SignInSuccess = response.data.data;
          if ("token" in responseData) {
            this.persistToken(responseData.token);
          }
          window.location.href = "/dashboard";
        })
        .catch((error) => {
          this.errorResponse = error.response?.data?.message || "";
        })
        .finally(() => {
          this.processing = false;
        });
    },
  },
});
</script>
