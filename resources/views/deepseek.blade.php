<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeepSeek Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .message-container {
            max-height: 70vh;
            overflow-y: auto;
        }
        .typing-indicator:after {
            content: '...';
            animation: typing 1.5s infinite;
        }
        @keyframes typing {
            0% { content: '.'; }
            33% { content: '..'; }
            66% { content: '...'; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <header class="mb-6">
            <h1 class="text-3xl font-bold text-center text-blue-600">DeepSeek Chat</h1>
        </header>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Chat messages container -->
            <div class="message-container p-4 space-y-4" id="messages">
                <!-- Messages will appear here -->
            </div>

            <!-- Input area -->
            <div class="border-t border-gray-200 p-4 bg-gray-50">
                <form id="chat-form" class="flex space-x-2">
                    @csrf
                    <input 
                        type="text" 
                        id="user-input" 
                        placeholder="Message DeepSeek..." 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        autocomplete="off"
                    >
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('chat-form');
            const userInput = document.getElementById('user-input');
            const messagesContainer = document.getElementById('messages');
            
            // Load conversation history from localStorage
            const conversation = JSON.parse(localStorage.getItem('deepseek_conversation')) || [];
            renderMessages(conversation);

            chatForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const message = userInput.value.trim();
                if (!message) return;

                // Add user message to conversation
                conversation.push({ role: 'user', content: message });
                saveConversation(conversation);
                renderMessages(conversation);
                userInput.value = '';
                
                // Show typing indicator
                messagesContainer.innerHTML += `
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">AI</div>
                        <div class="typing-indicator bg-blue-50 px-4 py-2 rounded-lg max-w-[80%]"></div>
                    </div>
                `;
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                try {
                    const response = await fetch('/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ messages: conversation })
                    });

                    const data = await response.json();
                    
                    // Remove typing indicator
                    const typingIndicators = document.querySelectorAll('.typing-indicator');
                    typingIndicators[typingIndicators.length - 1].remove();

                    if (data.choices && data.choices[0].message) {
                        // Add AI response to conversation
                        conversation.push(data.choices[0].message);
                        saveConversation(conversation);
                        renderMessages(conversation);
                    } else {
                        showError('Failed to get response from DeepSeek');
                    }
                } catch (error) {
                    showError('An error occurred while communicating with DeepSeek');
                }
            });

            function renderMessages(messages) {
                messagesContainer.innerHTML = '';
                messages.forEach(msg => {
                    const isAI = msg.role === 'assistant';
                    messagesContainer.innerHTML += `
                        <div class="flex items-start space-x-3 ${isAI ? 'justify-start' : 'justify-end'}">
                            ${isAI ? '<div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">AI</div>' : ''}
                            <div class="${isAI ? 'bg-blue-50' : 'bg-gray-100'} px-4 py-2 rounded-lg max-w-[80%]">
                                ${msg.content.replace(/\n/g, '<br>')}
                            </div>
                            ${!isAI ? '<div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold">You</div>' : ''}
                        </div>
                    `;
                });
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function saveConversation(conversation) {
                localStorage.setItem('deepseek_conversation', JSON.stringify(conversation));
            }

            function showError(message) {
                messagesContainer.innerHTML += `
                    <div class="text-red-500 text-sm p-2">
                        ${message}
                    </div>
                `;
            }
        });
    </script>
</body>
</html>