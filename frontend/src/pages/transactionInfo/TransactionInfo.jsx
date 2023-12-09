import React, {useEffect, useState} from 'react';
import {Link, useNavigate, useParams} from "react-router-dom";
import TransactionService from "../../services/TransactionService";
import Loader from "../../components/UI/loader/Loader";
import {formatDateFromResponse, formatDateWithTimeFromResponse} from "../../util/dateUtil";
import {Button, Container} from "react-bootstrap";
import {TRANSACTION_TYPES} from "../../constants/constants";

const TransactionInfo = () => {
    const params = useParams();
    const [isLoading, setIsLoading] = useState(true);
    const [transaction, setTransaction] = useState({});
    const navigate = useNavigate();

    useEffect(() => {
        TransactionService.getTransactionById(params.id)
            .then(response => {
                let data = response.data;
                data.transactionDate = formatDateWithTimeFromResponse(data.transaction_date.date);
                data.createdAt = formatDateWithTimeFromResponse(data.created_at.date);
                setTransaction(response.data);
            }).finally(() => {
                setIsLoading(false);
        });
    }, []);

    const handleDelete = (e) => {
        e.preventDefault();

        setIsLoading(true);
        TransactionService.deleteById(transaction.id)
            .then(response => {
                console.log(response);
                navigate('/transactions');
            }).finally(() => {
                setIsLoading(false);
        });
    }

    const handleUpdate = () => {
        navigate(`/transactions/${params.id}/update`);
    };

    const handleBack = () => {
        navigate('/transactions')
    };

    return (
        isLoading
        ?
        <Loader/>
        :
        <Container className="pt-3">
            <Button variant="secondary" className="mb-2" onClick={handleBack}>Back</Button>
            <div className="card">
                <div className="card-header d-flex justify-content-between">
                    <div>Amount</div>
                    <div>Category</div>
                    <div>Description</div>
                    <div>Transaction date</div>
                    <div>Created date</div>
                </div>
                <div className="card-body d-flex justify-content-between">
                    <div style={{color: transaction.type === TRANSACTION_TYPES.INCOME ? "green" : "red"}}>
                        {transaction.type === TRANSACTION_TYPES.INCOME ? '+' : '-'}{transaction.amount}
                    </div>
                    <div>{transaction.category.name}</div>
                    <div>{transaction.description}</div>
                    <div>{transaction.transactionDate}</div>
                    <div>{transaction.createdAt}</div>
                </div>
                <div className="card-footer d-flex justify-content-between">
                    <Button onClick={handleUpdate}>Update</Button>
                    <Button variant="danger" onClick={handleDelete}>Delete</Button>
                </div>
            </div>

        </Container>
    );
};

export default TransactionInfo;