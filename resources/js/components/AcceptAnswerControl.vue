<template>
    <div>
        <a
            v-if="canAcceptAnswer"
            title="Click to mark as accepted answer (Click again to undo)"
            class="mt-2"
            :class="status"
            @click.prevent="markAsAccepted"
        >
            <i class="fas fa-check fa-2x"></i>
        </a>

        <a
            v-else-if="!canAcceptAnswer && isBest"
            title="The question owner marked this answer as accepted answer"
            class="mt-2"
            :class="status"
        >
            <i class="fas fa-check fa-2x"></i>
        </a>
    </div>
</template>

<script>
import { eventBus } from "../events/event-bus";

import Answer from "../services/answer";

export default {
    name: "AcceptAnswerControl",
    props: {
        answer: {
            type: Object,
            required: true
        }
    },
    created: function() {
        eventBus.$on("accepted", id => {
            if (this.id !== id) {
                this.status = "";
            }
        });
    },
    data: function() {
        return {
            id: this.answer.id,
            status: this.answer.status,
            isBest: this.answer.is_best
        };
    },
    methods: {
        markAsAccepted: async function() {
            try {
                const response = await Answer.accept(this.id);

                const { id, status, is_best } = response.data.answer;

                this.id = id;
                this.status = status;
                this.isBest = is_best;

                eventBus.$emit("accepted", this.id);
            } catch (error) {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        }
    },
    computed: {
        canAcceptAnswer: function() {
            return this.$gate.allow("accept", "answer", this.answer);
        }
    }
};
</script>
