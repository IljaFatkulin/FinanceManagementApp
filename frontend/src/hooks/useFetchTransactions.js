    import { useEffect, useState } from "react";
    import TransactionService from "../services/TransactionService";
    import { formatDateFromResponse } from "../util/dateUtil";

    export const useFetchTransactions = (userData, selectedCategory) => {
        const [transactions, setTransactions] = useState([]);
        const [isLoading, setIsLoading] = useState(true);

        useEffect(() => {
            if (selectedCategory === 'all' || selectedCategory === undefined) {
                TransactionService.getUserTransactions(userData.id)
                    .then(response => {
                        setTransactions(formatResponse(response));
                    }).finally(() => {
                        setIsLoading(false);
                });
            } else {
                TransactionService.getUserTransactionsByCategoryId(userData.id, selectedCategory)
                    .then(response => {
                        setTransactions(formatResponse(response));
                    }).finally(() => {
                        setIsLoading(false);
                });
            }
        }, [userData.id, selectedCategory]);

        return { transactions, isLoading };
    };

    const formatResponse = (response) => {
        return response.data.map(transaction => ({
            ...transaction,
            transactionDate: formatDateFromResponse(transaction.transaction_date.date)
        }));
    }