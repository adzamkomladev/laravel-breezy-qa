<template>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h3>Your answer</h3>
                    </div>
                    <hr />
                    <form @submit.prevent="createAnswer">
                        <div class="form-group">
                            <label for="body">Your answer</label>
                            <textarea
                                v-model="body"
                                name="body"
                                id="body"
                                cols="30"
                                rows="7"
                                placeholder="Enter your answer..."
                                class="form-control"
                                :class="invalidClass"
                            >
                            </textarea>

                            <div v-if="isInvalid" class="invalid-feedback">
                                <strong>Invalid answer provided</strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <button
                                :disabled="isDisabled"
                                type="submit"
                                class="btn btn-lg btn-outline-primary"
                            >
                                Submit answer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Answer from "../services/answer";

export default {
    name: "AnswerForm",
    props: {
        questionId: {
            type: Number,
            required: true
        }
    },
    data: function() {
        return {
            body: ""
        };
    },
    methods: {
        createAnswer: async function() {
            try {
                const response = await Answer.store(this.questionId, this.body);

                const { answer, message } = response.data;

                this.$emit("created", answer);

                this.$toast.success(message, "Success", {
                    timeout: 3000
                });

                this.body = "";
            } catch {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        }
    },
    computed: {
        canMarkInvalid: function() {
            return this.body.length > 1;
        },
        isInvalid: function() {
            if (this.canMarkInvalid) {
                return this.isDisabled;
            }

            return false;
        },
        isDisabled: function() {
            return this.body.length < 10;
        },
        invalidClass: function() {
            return {
                "is-invalid": this.isInvalid
            };
        }
    }
};
</script>
