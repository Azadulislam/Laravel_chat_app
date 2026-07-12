<style>
    html, body {
        position: relative;
        min-height: 100%;
    }
    #contextMenu {
        position: fixed;
        z-index: 10001;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        padding: 8px 0;
        min-width: 160px;
        display: none;
    }
    #contextMenu button {
        width: 100%;
        background: none;
        border: none;
        padding: 10px 16px;
        text-align: left;
        cursor: pointer;
        font-size: 14px;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    #contextMenu button:hover {
        background: #f3f4f6;
    }
    #toastContainer {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10002;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .toast {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        padding: 16px 20px;
        min-width: 280px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideIn 0.3s ease-out;
    }
    .toast.success {
        border-left: 4px solid #10b981;
    }
    .toast.error {
        border-left: 4px solid #ef4444;
    }
    .toast .icon {
        font-size: 20px;
    }
    .toast .message {
        font-size: 14px;
        color: #374151;
        flex: 1;
    }
    .toast .close {
        background: none;
        border: none;
        cursor: pointer;
        color: #9ca3af;
        font-size: 18px;
        padding: 0;
    }
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
</style>
