<template>
    <div>
        <a
            :class="unAuthenticatedClass"
            @click.prevent="upvote"
            :title="'This ' + text + ' is useful'"
            class="upvote"
        >
            <i class="fas fa-caret-up fa-3x"></i>
        </a>
        <span class="votes-count">{{ votesCount }}</span>
        <a
            :class="unAuthenticatedClass"
            @click.prevent="downvote"
            :title="'This ' + text + ' is not useful'"
            class="downvote"
        >
            <i class="fas fa-caret-down fa-3x"></i>
        </a>

        <FavouriteQuestionControl v-if="isQuestion" :question="modelCopy" />
        <AcceptAnswerControl v-else :answer="modelCopy" />
    </div>
</template>

<script>
import Auth from "../services/auth";
import Answer from "../services/answer";
import Question from "../services/question";

import FavouriteQuestionControl from "./FavouriteQuestionControl";
import AcceptAnswerControl from "./AcceptAnswerControl";

export default {
    name: "VoteControl",
    props: {
        model: {
            type: Object,
            required: true
        },
        text: {
            type: String,
            required: true
        }
    },
    components: {
        FavouriteQuestionControl,
        AcceptAnswerControl
    },
    data: function() {
        return {
            modelCopy: this.model,
            textCopy: this.text,
            isLoggedIn: Auth.isLoggedIn()
        };
    },
    methods: {
        vote: async function(vote) {
            try {
                const response = this.isQuestion
                    ? await Question.vote(this.modelCopy.id, vote)
                    : await Answer.vote(this.modelCopy.id, vote);

                this.modelCopy = response.data.model;

                this.$toast.success(response.data.message, "Success", {
                    timeout: 3000
                });
            } catch (error) {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        },
        upvote: async function() {
            await this.vote(1);
        },
        downvote: async function() {
            await this.vote(-1);
        }
    },
    computed: {
        isQuestion: function() {
            return this.textCopy === "question";
        },
        votesCount: function() {
            return this.modelCopy.votes_count || 0;
        },
        unAuthenticatedClass: function() {
            return !this.isLoggedIn ? "off" : "";
        }
    }
};
</script>
