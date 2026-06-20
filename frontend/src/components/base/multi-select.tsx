import { useState, useId } from "react";

export type Option = { label: string; value: string | number };

type MultiSelectProps = {
    label?: string;
    options: Option[];
    value: (string | number)[];
    onChange: (value: (string | number)[]) => void;
    placeholder?: string;
    error?: string;
    helpText?: string;
};

export const MultiSelect = ({ label, options, value, onChange, placeholder, error, helpText }: MultiSelectProps) => {
    const inputId = useId();
    const [selectedId, setSelectedId] = useState<string>("");

    const handleAdd = () => {
        if (!selectedId) return;
        const numId = Number(selectedId);
        const id = isNaN(numId) ? selectedId : numId;

        if (!value.includes(id)) {
            onChange([...value, id]);
        }
        setSelectedId("");
    };

    const handleRemove = (idToRemove: string | number) => {
        onChange(value.filter(id => id !== idToRemove));
    };

    const selectedOptions = value.map(id => options.find(o => o.value == id)).filter(Boolean) as Option[];

    return (
        <div className="d-flex flex-column gap-1">
            {label && <label htmlFor={inputId} className="form-label mb-0 fw-medium small text-muted">{label}</label>}
            <div className="d-flex gap-2 mb-1">
                <select
                    id={inputId}
                    className={`form-select ${error ? 'is-invalid' : ''}`}
                    value={selectedId}
                    onChange={(e) => setSelectedId(e.target.value)}
                >
                    <option value="">{placeholder || "Selecione..."}</option>
                    {options.filter(o => !value.includes(o.value)).map(opt => (
                        <option key={opt.value} value={opt.value}>{opt.label}</option>
                    ))}
                </select>
                <button
                    type="button"
                    className="btn btn-primary px-3"
                    onClick={handleAdd}
                    disabled={!selectedId}
                >
                    <i className="bi bi-plus"></i>
                </button>
            </div>

            {helpText && <div className="form-text mt-0 mb-2">{helpText}</div>}

            {error && <div className="invalid-feedback d-block mt-0 mb-2">{error}</div>}

            <div className="bg-light-subtle border rounded p-2" style={{ minHeight: '46px', borderStyle: 'dashed!important' }}>
                {selectedOptions.length === 0 && (
                    <div className="text-muted small text-center mt-1">Nenhum item selecionado</div>
                )}
                {selectedOptions.map(opt => (
                    <div key={opt.value} className="d-flex justify-content-between align-items-center mb-1">
                        <span className="small text-truncate" style={{ maxWidth: '90%' }}>{opt.label}</span>
                        <button
                            type="button"
                            className="btn py-0 px-2"
                            onClick={() => handleRemove(opt.value)}
                        >
                            <i className="bi bi-dash-square"></i>
                        </button>
                    </div>
                ))}
            </div>
        </div>
    );
};
