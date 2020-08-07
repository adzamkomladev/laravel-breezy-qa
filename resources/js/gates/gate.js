import Answer from "../policies/answer";
import Question from "../policies/question";

export default class Gate {
    constructor(authState) {
        this.authState = authState;

        this.policies = {
            answer: Answer,
            question: Question
        };
    }

    before() {
        return this.authState.isLoggedIn;
    }

    allow(action, type, model = null) {
        if (this.before()) {
            return this.policies[type][action](this.authState.user, model);
        }

        return false;
    }

    deny(action, type, model = null) {
        return !this.allow(action, type, model);
    }
}
