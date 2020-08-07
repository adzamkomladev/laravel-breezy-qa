export default class Answer {
    static delete(questionId, answerId) {
        return axios.delete(`/questions/${questionId}/answers/${answerId}`);
    }

    static update(questionId, answerId, data) {
        return axios.patch(
            `/questions/${questionId}/answers/${answerId}`,
            data
        );
    }

    static accept(id) {
        return axios.post(`/answers/${id}/accept`);
    }

    static vote(id, vote) {
        return axios.patch(`/vote-for-answer/${id}`, { vote });
    }

    static getAll(questionId) {
        return axios.get(`/questions/${questionId}/answers`);
    }

    static getByUrl(url) {
        return axios.get(url);
    }

    static store(questionId, body) {
        return axios.post(`/questions/${questionId}/answers`, { body });
    }
}
