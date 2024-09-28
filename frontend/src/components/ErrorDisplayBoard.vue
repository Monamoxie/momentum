<template>
    <div class="error-display-board">
        <div
            class="alert alert-danger pulse-effect"
            :class="{ 'alert-dismissible fade show': dismissible }"
        >
            <button
                v-show="dismissible"
                type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
            >
                <span aria-hidden="true">&times;</span>
            </button>
            <template v-if="typeof serverResponse == 'object'">
                <div v-for="(errorGroups, key) of serverResponse" :key="key">
                    <ul>
                        <li
                            v-for="(listOfErrors, index) of errorGroups"
                            :key="index"
                        >
                            <i class="fa fa-times-circle"></i>
                            {{ listOfErrors }}
                        </li>
                    </ul>
                </div>
            </template>
            <template v-else>
                <p>{{ serverResponse }}</p>
            </template>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
export default defineComponent({
    name: "ErrorDisplayBoard",
    props: {
        serverResponse: {
            type: [Array, String],
            required: true,
        },
        dismissible: {
            type: Boolean,
            default: false,
        },
    },
});
</script>
<style scoped>
.error-display-board ul li {
    list-style: none;
}
</style>
