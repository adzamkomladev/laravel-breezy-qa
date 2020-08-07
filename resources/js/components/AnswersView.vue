<template>
    <div>
        <div v-if="count" class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h2>{{ title }}</h2>
                        </div>

                        <hr />
                        <AnswerView
                            @deleted="onDeleted(index)"
                            v-for="(answer, index) in answers"
                            :answer="answer"
                            :key="answer.id"
                        />
                    </div>
                    <div v-if="nextPageUrl" class="mx-auto">
                        <button
                            @click="loadMoreAnswers"
                            class="btn btn-secondary my-3"
                        >
                            Load more answers
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <AnswerForm
            v-if="isLoggedIn"
            :question-id="questionId"
            @created="onCreated"
        />
    </div>
</template>

<script>
import Auth from "../services/auth";
import Answer from "../services/answer";

import AnswerView from "./AnswerView";
import AnswerForm from "./AnswerForm";

export default {
    name: "AnswersView",
    props: {
        question: {
            type: Object,
            required: true
        }
    },
    components: {
        AnswerView,
        AnswerForm
    },
    created: async function() {
        await this.initAnswers();
    },
    data: function() {
        return {
            answers: [],
            count: this.question.answers_count || 0,
            questionId: this.question.id,
            nextPageUrl: undefined
        };
    },
    methods: {
        refreshData: function({ data, next_page_url }) {
            this.answers.push(...data);
            this.nextPageUrl = next_page_url;
        },
        initAnswers: async function() {
            try {
                const response = await Answer.getAll(this.questionId);

                this.refreshData(response.data);
            } catch (error) {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        },
        loadMoreAnswers: async function() {
            try {
                const response = await Answer.getByUrl(this.nextPageUrl);

                this.refreshData(response.data);
            } catch (error) {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        },
        onDeleted: function(index) {
            this.answers.splice(index, 1);
            --this.count;
        },
        onCreated: function(answer) {
            this.answers.push(answer);
            ++this.count;
        }
    },
    computed: {
        title: function() {
            return `${this.count} answer${this.count > 1 ? "s" : ""}`;
        },
        isLoggedIn: function() {
            return Auth.isLoggedIn();
        }
    }
};
</script>

<style scoped></style>
