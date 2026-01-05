<!-- UniPart Chatbot Widget - Add this to your footer.php or before </body> tag -->

<!-- Font Awesome (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Chatbot Button */
    .chatbot-button {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: #007BFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.4);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .chatbot-button:hover {
        background: #0069D9;
        transform: scale(1.1);
    }

    .chatbot-button i {
        color: white;
        font-size: 28px;
    }

    .chatbot-button.active i.fa-message {
        display: none;
    }

    .chatbot-button i.fa-times {
        display: none;
    }

    .chatbot-button.active i.fa-times {
        display: block;
    }

    /* Chatbot Container */
    .chatbot-container {
        position: fixed;
        bottom: 100px;
        right: 30px;
        width: 380px;
        height: 550px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        z-index: 999;
        overflow: hidden;
    }

    .chatbot-container.active {
        display: flex;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Chatbot Header */
    .chatbot-header {
        background: #007BFF;
        color: white;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-header .avatar {
        width: 45px;
        height: 45px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .chatbot-header .info h3 {
        font-size: 18px;
        margin-bottom: 3px;
    }

    .chatbot-header .info p {
        font-size: 13px;
        opacity: 0.9;
    }

    /* Chat Messages */
    .chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        gap: 10px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.bot {
        align-items: flex-start;
    }

    .message.user {
        justify-content: flex-end;
    }

    .message-avatar {
        width: 35px;
        height: 35px;
        background: #007BFF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }

    .message.user .message-avatar {
        background: #6C757D;
    }

    .message-content {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
    }

    .message.bot .message-content {
        background: white;
        border: 1px solid #e0e0e0;
    }

    .message.user .message-content {
        background: #007BFF;
        color: white;
    }

    /* Quick Replies */
    .quick-replies {
        padding: 10px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        background: #f8f9fa;
        border-top: 1px solid #e0e0e0;
    }

    .quick-reply-btn {
        padding: 8px 14px;
        background: white;
        border: 1px solid #007BFF;
        color: #007BFF;
        border-radius: 20px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quick-reply-btn:hover {
        background: #007BFF;
        color: white;
    }

    /* Input Area */
    .chatbot-input {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid #e0e0e0;
        display: flex;
        gap: 10px;
    }

    .chatbot-input input {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #ced4da;
        border-radius: 24px;
        font-size: 14px;
        outline: none;
        font-family: 'Poppins', Arial, sans-serif;
    }

    .chatbot-input input:focus {
        border-color: #007BFF;
    }

    .chatbot-input button {
        width: 45px;
        height: 45px;
        background: #007BFF;
        border: none;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .chatbot-input button:hover {
        background: #0069D9;
        transform: scale(1.05);
    }

    .typing-indicator {
        display: none;
        padding: 12px 16px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        width: fit-content;
    }

    .typing-indicator.active {
        display: block;
    }

    .typing-indicator span {
        height: 8px;
        width: 8px;
        background: #007BFF;
        border-radius: 50%;
        display: inline-block;
        margin: 0 2px;
        animation: bounce 1.4s infinite;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes bounce {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-8px);
        }
    }

    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #007BFF;
        border-radius: 3px;
    }

    /* Typing cursor */
    .typing-cursor {
        display: inline-block;
        width: 2px;
        height: 1em;
        background: #007BFF;
        margin-left: 2px;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 49% {
            opacity: 1;
        }
        50%, 100% {
            opacity: 0;
        }
    }

    @media (max-width: 480px) {
        .chatbot-container {
            width: calc(100% - 40px);
            height: calc(100% - 140px);
            right: 20px;
            bottom: 100px;
        }
    }
</style>

<!-- Chatbot Toggle Button -->
<div class="chatbot-button" id="chatbotButton">
    <i class="fas fa-message"></i>
    <i class="fas fa-times"></i>
</div>

<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
        <div class="avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="info">
            <h3>UniPart Assistant</h3>
            <p>Online • Ready to help</p>
        </div>
    </div>

    <div class="chatbot-messages" id="chatbotMessages"></div>

    <div class="quick-replies" id="quickReplies"></div>

    <div class="chatbot-input">
        <input 
            type="text" 
            id="userInput" 
            placeholder="Type your message..."
            autocomplete="off"
        >
        <button id="sendButton">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>
</div>

<script src="assets/js/chatbot.js"></script>