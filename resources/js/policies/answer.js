export default class Answer {
    static update(user, answer) {
        return user.id === answer.user_id;
    }

    static accept(user, answer) {
        return user.id === answer.question.user_id;
    }

    static delete(user, answer) {
        return user.id === answer.user_id;
    }
}
