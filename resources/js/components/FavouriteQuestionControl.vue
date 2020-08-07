<template>
    <a
        :class="favouredStyleClassesObject"
        @click.prevent="toggleFavour"
        class="favourite mt-2"
        title="Click to mark as favourite question (Click again to undo)"
    >
        <i class="fas fa-star fa-2x"></i>
        <span class="favourites-count">{{ favouritesCount }}</span>
    </a>
</template>

<script>
import Auth from "../services/auth";
import Question from "../services/question";

export default {
    name: "FavouriteQuestionControl",
    props: {
        question: {
            type: Object,
            required: true
        }
    },
    data: function() {
        return {
            isLoggedIn: Auth.isLoggedIn(),
            isFavoured: this.question.is_favoured,
            favouritesCount: this.question.favourites_count
        };
    },
    methods: {
        toggleFavour: async function() {
            if (!this.isLoggedIn) {
                this.$toast.warning("You must login first", "Warning", {
                    timeout: 3000,
                    position: "bottomLeft"
                });
                return;
            }

            try {
                const response = await Question.toggleFavourite(
                    this.question.id
                );

                this.updateDataState(response.data);
            } catch (error) {
                this.$toast.error(error.response.data.message, "Error", {
                    timeout: 3000
                });
            }
        },
        updateDataState: function({ question }) {
            this.isFavoured = question.is_favoured;
            this.favouritesCount = question.favourites_count;
        }
    },
    computed: {
        favouredStyleClassesObject: function() {
            return {
                off: !this.isLoggedIn,
                favoured: this.isLoggedIn && this.isFavoured
            };
        }
    }
};
</script>
