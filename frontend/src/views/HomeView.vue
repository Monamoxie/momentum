<script lang="ts">
import { defineComponent } from "vue";
import TheWelcome from "@/components/TheWelcome.vue";
import ErrorDisplayBoard from "@/components/ErrorDisplayBoard.vue";
import SuccessDisplayBoard from "@/components/SuccessDisplayBoard.vue";
import { useAuthStore } from "../stores/auth";
import { mapActions } from "pinia";

export default defineComponent({
    name: "Home",
    data() {
        return {
            email: "test@test.com",
            password: "123456",
            passwordConfirmation: "123456",
            processing: false,
            errorResponse: [] as string[] | string,
            successResponse: "" as string,
            successUrl: "" as string,
            subscribeSuccess: false,
            isValidated: false,
        };
    },
    components: {
        ErrorDisplayBoard,
        SuccessDisplayBoard,
    },
    methods: {
        ...mapActions(useAuthStore, ["signup", "login"]),
        async createAccount() {
            this.validate();
            if (this.isValidated) {
                this.processing = true;
                this.errorResponse = "";
                this.successResponse = "";

                try {
                    const authStore = useAuthStore();
                    const response = await authStore.signup({
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.passwordConfirmation,
                    });

                    this.successResponse =
                        "Account has been successfully created. Please Sign In";
                    this.successUrl = "/signin";
                    this.subscribeSuccess = true;
                } catch (error: any) {
                    const errors = error.response.data;
                    this.errorResponse = Object.values(errors);
                } finally {
                    this.processing = false;
                }
            }
        },
        validate() {
            this.isValidated = false;
            if (!this.isEmailValid) {
                this.errorResponse = "Please enter a valid email address.";
                return;
            }
            if (this.password !== this.passwordConfirmation) {
                this.errorResponse = "Passwords do not match";
                return;
            }
            this.isValidated = true;
        },
    },
    computed: {
        isEmailValid(): boolean {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(this.email);
        },
    },
});
</script>

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
                    <form @submit.prevent="createAccount">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                v-model="email"
                                class="form-control"
                                placeholder="Email address"
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
                            <label for="password">Confirm Password</label>
                            <input
                                type="password"
                                v-model="passwordConfirmation"
                                class="form-control"
                                placeholder="Password Confirmation"
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
                                <span v-else>Create Account</span>
                            </button>
                        </div>
                    </form>
                </div>
                <SuccessDisplayBoard
                    v-else
                    :server-response="successResponse"
                    :success-url="successUrl"
                />
            </div>
            <div class="col-md-6 side-hero">
                <img src="@/assets/images/hero.jpg" alt="Hero Image" />
            </div>
        </div>
    </div>
</template>
