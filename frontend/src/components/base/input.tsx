import { forwardRef, InputHTMLAttributes, useId } from "react";

export interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    label?: string;
    error?: string;
}

export const Input = forwardRef<HTMLInputElement, InputProps>(
    ({ label, error, className = "", id, ...props }, ref) => {
        const generatedId = useId();
        const inputId = id || generatedId;

        return (
            <div className="d-flex flex-column gap-1">
                {label && <label htmlFor={inputId} className="form-label mb-0 fw-medium small text-muted">{label}</label>}
                <input
                    id={inputId}
                    ref={ref}
                    className={`form-control ${error ? "is-invalid" : ""} ${className}`}
                    {...props}
                />
                {error && <div className="invalid-feedback d-block">{error}</div>}
            </div>
        );
    }
);

Input.displayName = "Input";
