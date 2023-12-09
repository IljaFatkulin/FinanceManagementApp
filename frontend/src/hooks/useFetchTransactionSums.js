import {useEffect, useState} from "react";
import TransactionService from "../services/TransactionService";
import {processSumData} from "../util/formUtil";
import {TRANSACTION_TYPES} from "../constants/constants";

export const useFetchTransactionSums = (userData) => {
    const [incomeSum, setIncomeSum] = useState([]);
    const [expenseSum, setExpenseSum] = useState([]);
    const [isLoading, setIsLoading] = useState(true);

    useEffect(() => {
        TransactionService.getTransactionsSumByCategoryAndType(userData.id)
            .then(response => {
                setIncomeSum(processSumData(response.data, TRANSACTION_TYPES.INCOME));
                setExpenseSum(processSumData(response.data, TRANSACTION_TYPES.EXPENSE));
            }).finally(() => {
                setIsLoading(false);
                return incomeSum;
        });
    }, [userData.id]);

    return {incomeSum, expenseSum, isLoading};
}