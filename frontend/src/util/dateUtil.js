export const formatDateFromResponse = (dateStr) => {
    let date = new Date(dateStr);
    // date.getMonth starts from 0 => +1
    return date.getFullYear() + '-'
        + (date.getMonth()+1).toString().padStart(2, '0') + '-'
        + date.getDate().toString().padStart(2, '0');
}

export const formatDateWithTimeFromResponse = (dateStr) => {
    let date = new Date(dateStr);
    // date.getMonth starts from 0 => +1
    return date.getFullYear() + '-'
        + (date.getMonth()+1).toString().padStart(2, '0') + '-'
        + date.getDate().toString().padStart(2, '0') + ' '
        + date.getHours() + ':'
        + date.getMinutes();
}

export const getCurrentDateTimeForInput = () => {
    const now = new Date();
    const year = now.getFullYear();
    // date.getMonth starts from 0 => +1
    const month = (now.getMonth() + 1).toString().padStart(2, '0');
    const day = now.getDate().toString().padStart(2, '0');
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');

    return `${year}-${month}-${day}T${hours}:${minutes}`;
};