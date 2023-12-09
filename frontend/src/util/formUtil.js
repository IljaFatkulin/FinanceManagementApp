

export const handleChange = (form, setForm) => (e) => {
    const { name, value } = e.target;
    setForm(prevForm => ({
        ...prevForm,
        [name]: value
    }));
};

export const handleAmountChange = (form, setForm) => (e) => {
    let { value } = e.target;
    const regex = /^\d*\.?\d{0,2}$/;

    if(value === '' || regex.test(value)) {
        setForm(prevForm => ({
            ...prevForm,
            amount: value
        }));
    }
};

export const processSumData = (data, type) => {
    return data.filter(item => item.type === type).map(item => ({
        x: item.category_name,
        y: parseFloat(item.total_amount)
    }));
};