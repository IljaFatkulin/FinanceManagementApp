import {useEffect, useState} from "react";
import CategoryService from "../services/CategoryService";

export const useFetchCategories = () => {
    const [categories, setCategories] = useState([]);
    const [isLoading, setIsLoading] = useState(true);
    const [reload, setReload] = useState(true);

    useEffect(() => {
        CategoryService.getAllUserCategories()
            .then(response => {
                setCategories(response.data);
            }).finally(() => {
                setIsLoading(false);
        });
    }, [reload]);

    const refreshCategories = () => setReload(!reload);

    return {categories, isLoading, refreshCategories};
}