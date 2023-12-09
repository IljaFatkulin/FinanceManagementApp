import React from 'react';
import {useNavigate} from "react-router-dom";
import {TRANSACTION_TYPES} from "../../constants/constants";
import {Button} from "react-bootstrap";

const ShowTransactions = ({transactions}) => {
    const navigate = useNavigate();

    const handleOpen = (id) => {
        navigate('/transactions/' + id);
    }

    return (
        <div>
            <div className="card">
                <div className="card-header d-flex gap-5 ps-5">
                    <div>Amount</div>
                    <div>Category</div>
                    <div>Transaction date</div>
                </div>
            </div>
            {transactions.length
                ?
                transactions.map(transaction =>
                <div className="card" key={transaction.id}>
                    <div className="card-body">
                        <div className="d-flex align-items-center justify-content-between ps-5 pe-5">
                            <div style={{color: transaction.type === TRANSACTION_TYPES.INCOME ? "green" : "red"}}>
                                {transaction.type === TRANSACTION_TYPES.INCOME ? '+' : '-'}{transaction.amount}
                            </div>
                            <div>{transaction.category.name}</div>
                            <div>{transaction.transactionDate}</div>
                            <div><Button onClick={() => handleOpen(transaction.id)} style={{height:"30px", padding: "2px 10px"}}>Open</Button></div>
                        </div>
                    </div>
                </div>
                )
                :
                <p>Transactions not found</p>
            }
        </div>
    );
};

export default ShowTransactions;