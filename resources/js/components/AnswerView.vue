<template>
    <div class="media post">
        <div class="d-flex flex-column vote-controls">
            <VoteControl :model="answer" text="answer" />
        </div>

        <form
            class="media-body"
            v-if="isEditing"
            @submit.prevent="updateAnswer"
        >
            <div class="form-group">
                <label for="body">Edit your answer</label>
                <textarea
                    v-model="bodyFormValue"
                    id="body"
                    cols="30"
                    rows="7"
                    class="form-control"
                    :class="fieldClassStyleObject"
                >
                </textarea>
                <div v-if="!isFieldValid" class="invalid-feedback">
                    <strong>Body is required!</strong>
                </div>
            </div>
            <div class="form-group">
                <button
                    :disabled="!isFieldValid"
                    type="submit"
                    class="btn btn-lg btn-primary"
                >
                    Update answer
                </button>
                <button
                    @click="cancelUpdateAnswer"
                    type="button"
                    class="btn btn-lg btn-outline-primary"
                >
                    Cancel
                </button>
            </div>
        </form>
        <div class="media-body" v-else>
            <div v-html="bodyHtml"></div>
            <div class="row">
                <div class="col-md-4">
                    <button
                        v-if="canUpdateAnswer"
                        @click="openEditAnswerForm"
                        class="btn btn-sm btn-outline-info"
                    >
                        Edit
                    </button>

                    <button
                        v-if="canDeleteAnswer"
                        @click="deleteAnswer"
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                    >
                        Delete
                    </button>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <UserInfo text="Answered" :model="answer" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Answer from "../services/answer";

import VoteControl from "./VoteControl";
import UserInfo from "./UserInfo";

export default {
    name: "AnswerView",
    props: {
        answer: {
            type: Object,
            required: true
        }
    },
    components: {
        VoteControl,
        UserInfo
    },
    data: function() {
        return {
            isEditing: false,
            id: this.answer.id,
            questionId: this.answer.question_id,
            bodyHtml: this.answer.body_html,
            body: this.answer.body,
            bodyFormValue: this.answer.body
        };
    },
    methods: {
        openEditAnswerForm: function() {
            this.isEditing = true;
        },
        updateAnswer: async function() {
            try {
                const response = await Answer.update(this.questionId, this.id, {
                    body: this.bodyFormValue
                });

                const answer = response.data.answer;

                this.id = answer.id;
                this.questionId = answer.question_id;
                this.bodyHtml = answer.body_html;
                this.body = answer.body;

                this.bodyFormValue = answer.body;

                this.isEditing = false;

                this.$toast.success(response.data.message, "Success", {
                    timeout: 3000
                });
            } catch (error) {
                this.bodyFormValue = this.body;

                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        },
        cancelUpdateAnswer: function() {
            this.bodyFormValue = this.body;
            this.isEditing = false;
        },
        deleteAnswer: async function() {
            this.$toast.question("Are you sure about that?", "Confirm", {
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: "once",
                id: "question",
                zindex: 999,
                position: "center",
                buttons: [
                    [
                        "<button><b>YES</b></button>",
                        async (instance, toast) => {
                            try {
                                const response = await Answer.delete(
                                    this.questionId,
                                    this.id
                                );

                                this.$emit("deleted");
                            } catch (error) {
                                this.$toast.error(
                                    error.response.data.message,
                                    "Error",
                                    { timeout: 3000 }
                                );
                            }

                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        },
                        true
                    ],
                    [
                        "<button>NO</button>",
                        function(instance, toast) {
                            instance.hide(
                                { transitionOut: "fadeOut" },
                                toast,
                                "button"
                            );
                        }
                    ]
                ]
            });
        }
    },
    computed: {
        isFieldValid: function() {
            return this.bodyFormValue.trim().length > 0;
        },
        fieldClassStyleObject: function() {
            return { "is-invalid": !this.isFieldValid };
        },
        canUpdateAnswer: function() {
            return this.$gate.allow("update", "answer", this.answer);
        },
        canDeleteAnswer: function() {
            return this.$gate.allow("delete", "answer", this.answer);
        }
    }
};
</script>
