import React from 'react';

type ConfirmModalProps = {
    isOpen: boolean;
    title: string;
    message: string | React.ReactNode;
    onConfirm: () => void;
    onCancel: () => void;
    isConfirming?: boolean;
};

export const ConfirmModal: React.FC<ConfirmModalProps> = ({
    isOpen,
    title,
    message,
    onConfirm,
    onCancel,
    isConfirming = false
}) => {
    if (!isOpen) return null;

    return (
        <>
            <div className="modal show d-block" tabIndex={-1} style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
                <div className="modal-dialog modal-dialog-centered">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title">{title}</h5>
                            <button 
                                type="button" 
                                className="btn-close" 
                                onClick={onCancel} 
                                aria-label="Close" 
                                disabled={isConfirming}
                            ></button>
                        </div>
                        <div className="modal-body">
                            {message}
                        </div>
                        <div className="modal-footer">
                            <button 
                                type="button" 
                                className="btn btn-secondary" 
                                onClick={onCancel} 
                                disabled={isConfirming}
                            >
                                Cancelar
                            </button>
                            <button 
                                type="button" 
                                className="btn btn-danger" 
                                onClick={onConfirm} 
                                disabled={isConfirming}
                            >
                                {isConfirming ? 'Excluindo...' : 'Excluir'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};
