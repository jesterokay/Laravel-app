<!DOCTYPE html>
<html lang="en" x-data="{ isDark: false }" :class="{ 'dark': isDark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deepseek AI</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🤖</text></svg>">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked@4.0.0/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/lib/core.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/lib/languages/cpp.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/lib/languages/php.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/lib/languages/python.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/lib/languages/javascript.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/styles/atom-one-light.min.css" id="highlight-style">
    <style>
        .code-block {
            width: 100%;
            overflow-x: auto;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 0.5rem;
            background-color: #f8f9fa;
            color: #212529;
        }
        .dark .code-block {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        pre, code {
            font-family: 'Fira Code', monospace;
            font-size: 0.875rem;
        }
        .chat-container-fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        textarea {
            resize: none;
            overflow-y: hidden;
        }
        .fade-in-out {
            transition: opacity 0.5s, transform 0.5s;
        }
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body>
    <div
        x-data="chatApp()"
        x-init="initTheme(); initSessionMessages()"
        class="chat-container-fullscreen bg-white dark:bg-gray-900 transition-colors duration-300 relative"
        :class="{ 'dark': isDark }"
    >
        <div class="flex justify-between items-center p-4 border-b bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 flex-shrink-0">
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">Deepseek AI</h1>
            <div class="flex items-center gap-2">
                <a href="/"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition no-underline"
                    title="Close Chat"
                >
                    Close
                </a>
                <button
                    @click="clearChat"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 transition"
                    title="New Chat"
                >
                    New Chat
                </button>
                <button
                    @click="toggleTheme"
                    class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition"
                    title="Toggle Theme"
                >
                    <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
            </div>
        </div>

        <div
            id="chat-box"
            class="flex-grow overflow-y-auto p-4 space-y-4 bg-gray-50 dark:bg-gray-800"
            style="scroll-behavior: smooth;"
        >
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div class="flex flex-col max-w-2xl">
                        <div
                            :class="msg.role === 'user' ? 'bg-blue-600 text-white self-end' : msg.role === 'error' ? 'bg-red-100 dark:bg-red-900 text-red-900 dark:text-red-200' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-200'"
                            class="px-4 py-3 rounded-lg break-words shadow-sm"
                        >
                            <div x-html="renderMessage(msg.text)"></div>
                            <div x-show="msg.role === 'assistant'" class="mt-2 flex justify-end">
                                <button
                                    @click="copyToClipboard(msg.text)"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 transition"
                                    title="Copy Response"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1" :class="msg.role === 'user' ? 'self-end' : 'self-start'" x-text="msg.timestamp"></div>
                    </div>
                </div>
            </template>
            <div x-show="isLoading" class="flex justify-start">
                <div class="bg-gray-200 dark:bg-gray-700 px-4 py-3 rounded-lg">
                    <span class="animate-pulse text-gray-900 dark:text-gray-200">Thinking...</span>
                </div>
            </div>
        </div>

        <form @submit.prevent="sendMessage" class="flex gap-3 items-start p-4 border-t bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800 flex-shrink-0">
            <textarea
                x-ref="chatInput"
                x-model="input"
                @keydown.enter="handleKeydown"
                @input="adjustTextareaHeight"
                class="flex-grow border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                placeholder="Type your message... (Shift + Enter for new line)"
                required
                rows="1"
                x-init="$el.value = '{{ old('prompt') }}' || ''"
            ></textarea>
            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 transition disabled:opacity-50 self-end"
                :disabled="isLoading"
            >
                Send
            </button>
        </form>

        <div
            x-show="showCopyMessage"
            x-transition:enter="fade-in-out"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="fade-in-out"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="absolute bottom-20 left-1/2 -translate-x-1/2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 text-sm px-4 py-2 rounded-md shadow-lg"
            style="display: none;"
        >
            <span x-text="copySuccessMessage"></span>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatApp', () => ({
                input: '',
                messages: [],
                isLoading: false,
                isDark: false,
                showCopyMessage: false,
                copySuccessMessage: '',
                
                initTheme() {
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme === 'dark' || savedTheme === 'light') {
                        this.isDark = savedTheme === 'dark';
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                    this.applyTheme();
                    this.$nextTick(() => this.applyTheme());
                },
                
                initSessionMessages() {
                    const conversation = @json($conversation ?? []);
                    const deepseekError = @json(session('deepseek_error'));
                    const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    conversation.forEach(msg => {
                        this.messages.push({
                            role: msg.role,
                            text: msg.content,
                            timestamp: timestamp
                        });
                    });
                    if (deepseekError) {
                        this.messages.push({ role: 'error', text: deepseekError, timestamp });
                    }
                    if (conversation.length || deepseekError) {
                        this.scrollToBottom();
                    }
                },
                
                toggleTheme() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    this.applyTheme();
                },
                
                applyTheme() {
                    const highlightStyle = document.getElementById('highlight-style');
                    const htmlEl = document.documentElement;
                    if (this.isDark) {
                        htmlEl.classList.add('dark');
                        highlightStyle.href = 'https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/styles/atom-one-dark.min.css';
                    } else {
                        htmlEl.classList.remove('dark');
                        highlightStyle.href = 'https://cdn.jsdelivr.net/npm/highlight.js@11.7.0/styles/atom-one-light.min.css';
                    }
                },
                
                copyToClipboard(text) {
                    const cleanText = text.replace(/```[\w]*\n([\s\S]*?)\n```/g, '$1').trim();
                    navigator.clipboard.writeText(cleanText).then(() => {
                        this.copySuccessMessage = 'Response copied to clipboard!';
                        this.showCopyMessage = true;
                        setTimeout(() => this.showCopyMessage = false, 2000);
                    }).catch(() => {
                        this.copySuccessMessage = 'Failed to copy response.';
                        this.showCopyMessage = true;
                        setTimeout(() => this.showCopyMessage = false, 2000);
                    });
                },
                
                clearChat() {
                    this.messages = [];
                    this.isLoading = true;
                    fetch('/deepseek/clear', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(`HTTP error! Status: ${res.status}`);
                        }
                        this.isLoading = false;
                    })
                    .catch((e) => {
                        this.isLoading = false;
                        console.error('Clear Chat Error:', e);
                        this.messages.push({
                            role: 'error',
                            text: `Sorry, failed to clear chat: ${e.message}`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                        this.scrollToBottom();
                    });
                },
                
                renderMessage(text) {
                    if (text.includes('```')) {
                        const codeMatch = text.match(/```(\w+)?\n([\s\S]*?)\n```/);
                        if (codeMatch) {
                            const language = codeMatch[1] || 'plaintext';
                            const code = codeMatch[2];
                            if (typeof hljs !== 'undefined') {
                                const highlighted = hljs.highlight(code, { language, ignoreIllegals: true }).value;
                                return `<pre class="code-block"><code class="hljs ${language}">${highlighted}</code></pre>`;
                            }
                        }
                    }
                    if (typeof marked !== 'undefined') {
                        return marked.parse(text);
                    }
                    return text;
                },
                
                handleKeydown(event) {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault();
                        this.sendMessage();
                    }
                },
                
                adjustTextareaHeight(event) {
                    const textarea = event.target;
                    textarea.style.height = 'auto';
                    textarea.style.height = (textarea.scrollHeight) + 'px';
                },
                
                sendMessage() {
                    if (!this.input.trim()) return;

                    const timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    this.messages.push({ role: 'user', text: this.input, timestamp });

                    let userText = this.input;
                    this.isLoading = true;

                    this.input = '';
                    this.$nextTick(() => {
                        this.$refs.chatInput.style.height = 'auto';
                    });

                    this.scrollToBottom();

                    fetch('{{ route("jester.deepseek.chat_page") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ prompt: userText }),
                    })
                    .then(res => {
                        if (!res.ok) {
                            throw new Error(`HTTP error! Status: ${res.status}`);
                        }
                        return res.json();
                    })
                    .then(data => {
                        this.isLoading = false;
                        const reply = data.reply || 'Sorry, no response.';
                        this.messages.push({ 
                            role: 'assistant', 
                            text: reply, 
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) 
                        });
                        this.scrollToBottom();
                    })
                    .catch((e) => {
                        this.isLoading = false;
                        console.error('API Error:', e);
                        this.messages.push({
                            role: 'error',
                            text: `Sorry, an error occurred: ${e.message}`,
                            timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                        });
                        this.scrollToBottom();
                    });
                },
                
                scrollToBottom() {
                    this.$nextTick(() => {
                        const box = document.getElementById('chat-box');
                        if (box) {
                            box.scrollTop = box.scrollHeight;
                        }
                    });
                }
            }));
        });
    </script>
</body>
</html>