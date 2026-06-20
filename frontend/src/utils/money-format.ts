export const moneyFormat = (inputValue: string | number | null = 0, prefix: string = 'R$ ') => {
	const onlyNumbers = String(inputValue).replace(/\D/g, '');

	if (onlyNumbers === '') {
		return '';
	}

	let numberValue = parseFloat(onlyNumbers) / 100;

	const formattedValue = numberValue.toLocaleString('pt-BR', {
		minimumFractionDigits: 2,
		maximumFractionDigits: 2,
	});
	return prefix + formattedValue;
};
