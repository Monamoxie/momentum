<template>
  <section class="base-wrapper">
    <header class="header-content momentum-bx-shadow1">
      <div class="container d-flex">
        <div class="logo-container">
          <a href="/">
            <img src="@/assets/images/logo.png" alt="Subscriba Logo" />
          </a>
        </div>
        <div class="nav-menu">
          <nav class="navbar navbar-expand-lg main-nav">
            <button
              class="navbar-toggler"
              type="button"
              data-bs-toggle="collapse"
              data-bs-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent"
              aria-expanded="false"
              aria-label="Toggle navigation"
            >
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                  <a class="nav-link" href="/"
                    ><button class="btn btn-primary">Home</button></a
                  >
                </li>
                <template v-if="!isAuthenticated">
                  <li class="nav-item">
                    <a class="nav-link" href="/signin">
                      <button class="btn btn-primary">Sign In</button>
                    </a>
                  </li>
                </template>
                <template v-else>
                  <li class="nav-item">
                    <a class="nav-link" href="/dashboard">
                      <button class="btn btn-primary">Dashboard</button>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">
                      <button @click="process" class="btn btn-danger">
                        Logout
                      </button>
                    </a>
                  </li>
                </template>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <RouterView />
  </section>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import { useAuthStore } from "./stores/auth";
import { mapActions } from "pinia";

export default defineComponent({
  name: "Home",
  data() {
    return {
      isAuthenticated: false,
    };
  },
  methods: {
    ...mapActions(useAuthStore, [
      "getToken",
      "signout",
      "isCsrfTokenSet",
      "fetchCsrfToken",
    ]),
    process() {
      this.signout();
      window.location.href = "/";
    },
    checkAuth() {
      const token = this.getToken();
      this.isAuthenticated =
        token !== "" && token !== undefined && token !== null;
    },
  },
  mounted() {
    this.checkAuth();

    if (!this.isCsrfTokenSet()) {
      this.fetchCsrfToken();
    }
  },
});
</script>
