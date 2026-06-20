import React, { forwardRef, useMemo, useId } from 'react';
import { moneyFormat } from '../../utils/money-format';

interface MoneyInputProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'onChange'> {
    label?: string;
    onChange?: (e: any) => void;
}

export const MoneyInput = forwardRef<HTMLInputElement, MoneyInputProps>(({ label, id, onChange, value, ...props }, ref) => {
    const defaultId = useId();
    const inputId = id || `input-${defaultId}`;

	const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
		const originalOnChange = onChange;
		const newValue = moneyFormat(e.target.value, '').replace(/\./g, '').replace(',', '.');
		originalOnChange && originalOnChange({ ...e, target: { ...e.target, value: newValue || '0', name: props.name } });
	};

	const formattedValue = useMemo(() => {
        if (!value && value !== 0) return '';
		return moneyFormat(
			String(String(value).indexOf(',') > -1 ? value : Number(value).toFixed(2).toString()),
			'',
		);
	}, [value]);

	return (
        <div className="d-flex flex-column gap-1">
            {label && <label htmlFor={inputId} className="form-label mb-0 fw-medium small text-muted">{label}</label>}
            <div className="input-group">
                <span className="input-group-text">R$</span>
                <input
                    id={inputId}
                    ref={ref}
                    className="form-control"
                    {...props}
                    onChange={handleChange}
                    value={formattedValue}
                    autoComplete="off"
                    inputMode="numeric"
                />
            </div>
        </div>
	);
});

MoneyInput.displayName = 'MoneyInput';
