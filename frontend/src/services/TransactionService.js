import axios from "../configs/axiosConfig";

export default class TransactionService {
    static async getUserTransactions(userId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                axios.get('/transactions?user=' + userId)
                    .then(resolve)
                    .catch(reject)
            }, 500);
        });
        // return axios.get('/transactions?user=' + userId);
    }

    static async getUserTransactionsByCategoryId(userId, categoryId) {
        return axios.get(`/transactions?user=${userId}&category=${categoryId}`);
    }

    static async getTransactionById(id) {
        return axios.get('/transaction?id=' + id);
    }

    static async deleteById(id) {
        return axios.post('/transaction/delete', {
            id: id
        });
    }

    static async update(id, type, amount, description, transactionDate) {
        return axios.post('/transaction/update', {
            id: Number(id),
            type: type,
            amount: formatAmount(amount),
            description: description,
            transaction_date: transactionDate
        });
    }

    static async create(userId, categoryId, type, amount, description, transactionDate) {
        return axios.post('/transaction', {
            user_id: Number(userId),
            category_id: Number(categoryId),
            type: type,
            amount: formatAmount(amount),
            description: description,
            transaction_date: transactionDate
        })
    }

    static async getTransactionsSumByCategoryAndType(userId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                axios.get('/transactions/sum?user=' + userId)
                    .then(resolve)
                    .catch(reject)
            }, 1000);
        });
        // return axios.get('/transactions/sum?user=' + userId);
    }
}

const formatAmount = (amount) => {
    return parseFloat(parseFloat(amount).toFixed(2)) + 0.001;
};