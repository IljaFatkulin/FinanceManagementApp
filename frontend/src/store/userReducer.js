const defaultState = {
    id: '',
    email: '',
    firstname: '',
    lastname: '',
    role: '',
    token: ''
}

export const userReducer = (state = defaultState, action) => {
    switch (action.type) {
        case "SET_ALL":
            return action.payload;
        case "SET_EMAIL":
            return {...state, email: action.payload};
        case "SET_FIRSTNAME":
            return {...state, firstname: action.payload};
        case "SET_LASTNAME":
            return {...state, lastname: action.payload};
        case "SET_TOKEN":
            return {...state, token: action.payload};
        default:
            return state;
    }
}