<template>
    <div
        class="alert shadow-1 p-4 success-display-board pulse-effect text-center"
        :class="{ 'alert-dismissible fade show': dismissible }"
    >
        <svg
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 130.2 130.2"
        >
            <circle
                class="path circle"
                fill="none"
                stroke="#528e74"
                stroke-width="6"
                stroke-miterlimit="10"
                cx="65.1"
                cy="65.1"
                r="62.1"
            />
            <polyline
                class="path check"
                fill="none"
                stroke="#528e74"
                stroke-width="6"
                stroke-linecap="round"
                stroke-miterlimit="5"
                points="100.2,40.2 51.5,88.8 29.8,67.5 "
            />
        </svg>
        <div class="alert alert-success mt-3">
            <p class="alert-heading">{{ serverResponse }}</p>
            <div>
                <a
                    :href="successUrl"
                    v-if="successUrl"
                    class="btn btn-primary bubbly-button theme1"
                    type="submit"
                >
                    Continue
                </a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "SuccessDisplayBoard",
    props: {
        serverResponse: {
            type: String,
            required: true,
        },
        dismissible: {
            type: Boolean,
            default: false,
        },
        successUrl: {
            type: String,
            required: false,
            default: "",
        },
    },
};
</script>
