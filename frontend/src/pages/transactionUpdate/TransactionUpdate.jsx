import React, {useEffect, useState} from 'react';
import {useNavigate, useParams} from "react-router-dom";
import Loader from "../../components/UI/loader/Loader";
import TransactionService from "../../services/TransactionService";
import {formatDateWithTimeFromResponse} from "../../util/dateUtil";
import TransactionForm from "../../components/transactionForm/TransactionForm";

const TransactionUpdate = () => {
    const params = useParams();
    const [isLoading, setIsLoading] = useState(true);
    const navigate = useNavigate();
    const [form, setForm] = useState({
        type: '',
        amount: '',
        description: '',
        transactionDate: ''
    });

    useEffect(() => {
        TransactionService.getTransactionById(params.id)
            .then(response => {
                setForm({
                    type: response.data.type,
                    amount: parseFloat(response.data.amount).toFixed(2),
                    description: response.data.description,
                    transactionDate: formatDateWithTimeFromResponse(response.data.transaction_date.date)
                });
            }).finally(() => {
                setIsLoading(false);
        });
    }, []);

    const handleSubmit = () => {
        setIsLoading(true);
        TransactionService.update(params.id, form.type, form.amount, form.description, form.transactionDate)
            .then(response => {
                console.log(response);
                navigate(`/transactions/${params.id}`);
            }).finally(() => {
                setIsLoading(false);
        });
    };

    return (
        isLoading
        ?
        <Loader/>
        :
        <div>
            <TransactionForm handleSubmit={handleSubmit} form={form} setForm={setForm}/>
        </div>
    );
};

export default TransactionUpdate;