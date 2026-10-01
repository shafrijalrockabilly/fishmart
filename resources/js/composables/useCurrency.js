export function useCurrency(amount) {
    if (amount === null || amount === undefined) return 'Rp0';
    return 'Rp' + Number(amount).toLocaleString('id-ID');
}
