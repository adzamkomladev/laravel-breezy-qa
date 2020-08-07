export default class Question {
    static toggleFavourite(id) {
        return axios.patch(`/questions/${id}/toggle-favourite`);
    }

    static vote(id, vote) {
        return axios.patch(`/vote-for-question/${id}`, { vote });
    }
}
