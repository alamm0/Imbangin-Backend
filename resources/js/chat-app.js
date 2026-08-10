import React, { useState } from 'react';
import ReactDOM from 'react-dom/client';
import axios from 'axios';

axios.defaults.headers.common.Accept = 'application/json';

const apiBaseUrl = window.location.origin || 'http://127.0.0.1:8000';

function ChatPanel() {
    const [draft, setDraft] = useState('');
    const [thread, setThread] = useState([
        React.createElement('div', {
            key: 'welcome',
            style: {
                background: '#ffffff',
                color: '#222',
                border: '1px solid #e8e0dc',
                padding: '10px 14px',
                borderRadius: 12,
                maxWidth: '82%',
                whiteSpace: 'pre-wrap',
                lineHeight: 1.5,
            },
        }, 'Halo! Saya IMBANGIN AI. Saya siap membantu Anda terkait kesehatan, jadwal, dan kondisi mental hari ini.'),
    ]);
    const [working, setWorking] = useState(false);
    const [authNotice, setAuthNotice] = useState('');

    const getAuthHeaders = () => {
        const token = localStorage.getItem('token');
        return {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
        };
    };

    async function sendToAi(e) {
        e.preventDefault();
        const userText = draft.trim();
        if (!userText || working) return;

        const token = localStorage.getItem('token');
        if (!token) {
            setAuthNotice('Silakan login terlebih dahulu untuk menggunakan chat AI.');
            setThread((prev) => [
                ...prev,
                React.createElement('div', {
                    key: `notice-${Date.now()}`,
                    style: {
                        alignSelf: 'flex-start',
                        background: '#fff5f5',
                        color: '#8a1f2d',
                        border: '1px solid #f3c6cf',
                        padding: '10px 14px',
                        borderRadius: 12,
                        maxWidth: '82%',
                    },
                }, 'Silakan login terlebih dahulu untuk menggunakan chat AI.'),
            ]);
            return;
        }

        setAuthNotice('');

        const userEntry = React.createElement('div', {
            key: `user-${Date.now()}`,
            style: {
                alignSelf: 'flex-end',
                background: '#6B1829',
                color: '#fff',
                padding: '10px 14px',
                borderRadius: 12,
                maxWidth: '82%',
                whiteSpace: 'pre-wrap',
                lineHeight: 1.5,
            },
        }, userText);

        setThread((prev) => [...prev, userEntry]);
        setDraft('');
        setWorking(true);

        try {
            const response = await axios.post(
                `${apiBaseUrl}/api/chat`,
                { message: userText },
                { headers: getAuthHeaders() }
            );

            const reply = response?.data?.reply || response?.data?.message || 'Maaf, saya belum bisa memberi jawaban saat ini.';
            setThread((prev) => [
                ...prev,
                React.createElement('div', {
                    key: `assistant-${Date.now()}`,
                    style: {
                        alignSelf: 'flex-start',
                        background: '#ffffff',
                        color: '#222',
                        border: '1px solid #e8e0dc',
                        padding: '10px 14px',
                        borderRadius: 12,
                        maxWidth: '82%',
                        whiteSpace: 'pre-wrap',
                        lineHeight: 1.5,
                    },
                }, reply),
            ]);
        } catch (error) {
            const message = error?.response?.data?.error || error?.response?.data?.message || error?.message || 'Terjadi masalah saat menghubungi AI.';
            setThread((prev) => [
                ...prev,
                React.createElement('div', {
                    key: `assistant-${Date.now() + 1}`,
                    style: {
                        alignSelf: 'flex-start',
                        background: '#ffffff',
                        color: '#222',
                        border: '1px solid #e8e0dc',
                        padding: '10px 14px',
                        borderRadius: 12,
                        whiteSpace: 'pre-wrap',
                        lineHeight: 1.5,
                    },
                }, `Maaf, ada masalah: ${message}`),
            ]);
        } finally {
            setWorking(false);
        }
    }

    return React.createElement(
        'div',
        { style: { maxWidth: 780, margin: '24px auto', fontFamily: 'sans-serif' } },
        React.createElement('div', { style: { marginBottom: 16 } },
            React.createElement('h2', { style: { margin: 0, color: '#6B1829', fontSize: '1.8rem' } }, 'IMBANGIN AI'),
            React.createElement('p', { style: { margin: '6px 0 0', color: '#5f5f5f' } }, 'Kirim pertanyaan Anda dan dapatkan respons dari backend Laravel Anda.')
        ),
        authNotice ? React.createElement('div', { style: { marginBottom: 12, padding: '10px 12px', borderRadius: 10, background: '#fff5f5', color: '#8a1f2d', border: '1px solid #f3c6cf' } }, authNotice) : null,
        React.createElement('div', {
            style: {
                border: '1px solid #e6d9d2',
                borderRadius: 16,
                padding: 18,
                minHeight: 360,
                background: '#fcfaf8',
                display: 'flex',
                flexDirection: 'column',
                gap: 10,
                boxShadow: '0 10px 30px rgba(107, 24, 41, 0.08)',
            },
        }, thread, working ? React.createElement('div', { style: { color: '#6b6b6b', fontStyle: 'italic' } }, 'AI sedang berpikir...') : null),
        React.createElement('form', { onSubmit: sendToAi, style: { marginTop: 12, display: 'flex', gap: 8 } },
            React.createElement('input', {
                value: draft,
                onChange: (e) => setDraft(e.target.value),
                placeholder: 'Tanyakan apa yang Anda butuhkan...',
                style: {
                    flex: 1,
                    padding: '10px 12px',
                    borderRadius: 10,
                    border: '1px solid #c8b6b0',
                    outline: 'none',
                },
                disabled: !localStorage.getItem('token'),
            }),
            React.createElement('button', {
                type: 'submit',
                disabled: working || !draft.trim() || !localStorage.getItem('token'),
                style: {
                    padding: '10px 16px',
                    borderRadius: 10,
                    border: 'none',
                    background: '#6B1829',
                    color: 'white',
                    cursor: working ? 'not-allowed' : 'pointer',
                },
            }, 'Kirim')
        )
    );
}

function App() {
    return React.createElement('div', {
        style: { minHeight: '100vh', background: 'linear-gradient(135deg, #f7efe8 0%, #fff 100%)', padding: '24px' },
    }, React.createElement(ChatPanel));
}

const mountNode = document.getElementById('app') ?? (() => {
    const element = document.createElement('div');
    element.id = 'app';
    document.body.prepend(element);
    return element;
})();

ReactDOM.createRoot(mountNode).render(
    React.createElement(React.StrictMode, null, React.createElement(App))
);
