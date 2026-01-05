// UniPart Chatbot - JavaScript Logic
// Place this file in: assets/js/chatbot.js

// Chatbot Knowledge Base
const knowledgeBase = {
    // General questions
    'hello|hi|hey|greetings|hi there|good morning|good afternoon': {
        response: "Hello! 👋 Welcome to UniPart! I'm here to help you find part-time jobs or post opportunities. How can I assist you today?",
        quickReplies: ['Find Jobs', 'Post a Job', 'How to Apply', 'About UniPart']
    },
    'what is unipart|about unipart|tell me about|what does unipart do': {
        response: "UniPart is a job-matching platform designed specifically for university students! 🎓 We connect students with part-time and online job opportunities from verified employers. Students can browse jobs, apply easily, and track their applications, while employers can post jobs and manage applicants.",
        quickReplies: ['Find Jobs', 'Register as Student', 'Register as Employer']
    },
    
    // Student-related
    'find job|search job|looking for job|browse jobs|job search|need job': {
        response: "Great! To find jobs on UniPart:\n\n1️⃣ Register/Login as a Student\n2️⃣ Go to 'Job Listings' page\n3️⃣ Use filters (job type, category, location, pay)\n4️⃣ Click on any job to view details\n5️⃣ Click 'Apply Now' button\n\nYou can track all your applications in your dashboard! 📊",
        quickReplies: ['How to Apply', 'Registration Process', 'Application Status']
    },
    'how to apply|application process|apply for job|submit application': {
        response: "Applying is simple! 📝\n\n1️⃣ Find a job you like\n2️⃣ Click 'Apply Now' on the job details page\n3️⃣ Make sure your profile is complete\n4️⃣ Your application is sent to the employer\n5️⃣ Track status in 'My Applications'\n\nEmployers will review and respond to your application. You'll get notifications about status changes! 🔔",
        quickReplies: ['Upload Resume', 'Edit Profile', 'Application Status']
    },
    'register|sign up|create account|how to register|registration': {
        response: "To register on UniPart:\n\n1️⃣ Click 'Register' button\n2️⃣ Choose your role: Student or Employer\n3️⃣ Fill in your details (name, email, password)\n4️⃣ Students: add department & skills\n5️⃣ Employers: add company name\n6️⃣ Click 'Sign Up'\n\nThen login and complete your profile! ✨",
        quickReplies: ['Student Features', 'Employer Features', 'Login Help']
    },
    'resume|upload resume|cv|curriculum vitae': {
        response: "To upload your resume:\n\n1️⃣ Login to your student account\n2️⃣ Go to 'My Profile'\n3️⃣ Find 'Upload Resume' section\n4️⃣ Choose your PDF/DOC file\n5️⃣ Click 'Save'\n\nEmployers can view and download your resume when you apply! 📄",
        quickReplies: ['Profile Tips', 'Find Jobs', 'Application Status']
    },
    'application status|track application|my applications|check status': {
        response: "To check your applications:\n\n1️⃣ Login to your dashboard\n2️⃣ Click 'My Applications'\n3️⃣ View all jobs you've applied to\n\nStatus types:\n✅ Accepted - Congrats!\n⏳ Pending - Under review\n❌ Rejected - Keep trying!\n\nYou'll also get notifications for status changes! 🔔",
        quickReplies: ['Find More Jobs', 'Edit Profile', 'Contact Support']
    },
    
    // Employer-related
    'post job|add job|create job listing|how to post|publish job': {
        response: "To post a job as an Employer:\n\n1️⃣ Login to your employer account\n2️⃣ Go to 'Post a Job'\n3️⃣ Fill in job details:\n   • Job title\n   • Job type (part-time/online)\n   • Category\n   • Pay rate\n   • Location\n   • Description\n4️⃣ Click 'Submit'\n\nYour job will be live and students can apply! 🎯",
        quickReplies: ['Manage Jobs', 'View Applicants', 'Employer Dashboard']
    },
    'manage job|edit job|delete job|update job': {
        response: "To manage your job postings:\n\n1️⃣ Go to 'Manage Jobs' in your dashboard\n2️⃣ View all your posted jobs\n3️⃣ Click 'Edit' to update details\n4️⃣ Click 'Delete' to remove a job\n\nYou can also view how many students applied to each job! 📊",
        quickReplies: ['View Applicants', 'Post New Job', 'Profile Settings']
    },
    'applicants|view applications|candidates|applications received': {
        response: "To view applicants:\n\n1️⃣ Go to 'View Applicants' in your dashboard\n2️⃣ Select a job to see all applications\n3️⃣ Review student profiles and resumes\n4️⃣ Accept or Reject applicants\n\nStudents will be notified of your decision! 👥",
        quickReplies: ['Accept/Reject Tips', 'Post Another Job', 'Employer Profile']
    },
    'employer account|employer registration|company account|employer sign up': {
        response: "To create an Employer account:\n\n1️⃣ Click 'Register'\n2️⃣ Select 'Employer' role\n3️⃣ Enter:\n   • Your name\n   • Email\n   • Password\n   • Company name\n4️⃣ Complete registration\n\nAdmin will verify your account, then you can post jobs! 🏢",
        quickReplies: ['Post a Job', 'Verification Process', 'Dashboard Overview']
    },
    
    // Technical/Support
    'forgot password|reset password|can\'t login|cannot login|password reset': {
        response: "To reset your password:\n\n1️⃣ Go to login page\n2️⃣ Click 'Forgot Password?'\n3️⃣ Enter your registered email\n4️⃣ Check your email for reset link\n5️⃣ Click the link and create new password\n\nIf you don't receive the email, check spam folder! 📧",
        quickReplies: ['Login Help', 'Contact Support', 'Registration']
    },
    'profile|edit profile|update profile|my profile': {
        response: "To edit your profile:\n\n1️⃣ Login to your account\n2️⃣ Click 'My Profile'\n3️⃣ Update your information:\n   • For Students: skills, department, resume\n   • For Employers: company details\n4️⃣ Click 'Save Changes'\n\nKeep your profile updated for better opportunities! 💼",
        quickReplies: ['Upload Resume', 'Change Password', 'Dashboard']
    },
    'dashboard|control panel|my account|my dashboard': {
        response: "Your dashboard shows:\n\n📊 Students:\n• Total applications\n• Application statuses\n• Quick links to jobs\n• Notifications\n\n🏢 Employers:\n• Total jobs posted\n• New applications\n• Quick access to manage jobs\n• Stats overview\n\nIt's your control center! 🎮",
        quickReplies: ['View Dashboard', 'Find Jobs', 'Post Job']
    },
    'notification|alerts|email notification': {
        response: "UniPart sends notifications for:\n\n🔔 Students:\n• Application accepted/rejected\n• New job recommendations\n• Profile completion reminders\n\n🔔 Employers:\n• New applications received\n• Application status updates\n\nStay updated with real-time alerts! ⚡",
        quickReplies: ['My Applications', 'Manage Jobs', 'Settings']
    },
    'contact|support|help|customer service|get help': {
        response: "Need more help? Contact us:\n\n📧 Email: support@unipart.com\n📱 Phone: +94 XX XXX XXXX\n💬 Live Chat: Available 9 AM - 6 PM\n\nOr visit our Help Center for detailed guides! We're here to help! 🤝",
        quickReplies: ['FAQ', 'Report Issue', 'Feedback']
    },
    'payment|salary|pay|wage|money': {
        response: "About payments:\n\n💰 Pay rates are set by employers and shown in job listings\n💳 Payment terms are agreed between employer and student\n📝 Both parties should discuss payment details before starting\n\nUniPart provides the platform but doesn't handle payments directly. Always clarify payment terms before accepting a job! ✅",
        quickReplies: ['Find Jobs', 'Job Details', 'Safety Tips']
    },
    'safe|safety|security|trust|scam': {
        response: "Your safety matters! 🛡️\n\n✅ All employers are verified by admin\n✅ Never share personal banking info in chat\n✅ Meet in public places for interviews\n✅ Read job details carefully\n✅ Report suspicious activity\n\nIf something seems wrong, contact our support team immediately! 🚨",
        quickReplies: ['Report Issue', 'Verified Employers', 'Contact Support']
    },
    
    // Features
    'features|what can i do|capabilities|what can you do': {
        response: "UniPart features:\n\n👨‍🎓 For Students:\n• Search & filter jobs\n• One-click applications\n• Track application status\n• Upload resume\n• Rate employers\n\n👔 For Employers:\n• Post unlimited jobs\n• View applicants\n• Accept/reject applications\n• Download resumes\n• Rate students\n\nEverything you need in one place! 🌟",
        quickReplies: ['Get Started', 'Register', 'Learn More']
    },
    'rating|review|feedback|rate': {
        response: "After job completion:\n\n⭐ Students can rate employers\n⭐ Employers can rate students\n\nRatings help build trust in the community! Both parties can leave feedback about their experience. Good ratings improve your profile visibility! 🌟",
        quickReplies: ['How to Rate', 'View Ratings', 'Profile Tips']
    },
    'job type|categories|what jobs|job categories': {
        response: "Available job categories:\n\n💻 Technology & IT\n📚 Tutoring & Education\n🎨 Design & Creative\n✍️ Writing & Content\n📊 Data Entry\n🛍️ Sales & Marketing\n🍽️ Food & Service\n📱 Online/Remote jobs\n...and more!\n\nFilter by type, location, and pay rate! 🎯",
        quickReplies: ['Browse Jobs', 'Part-Time Jobs', 'Online Jobs']
    },
    'thanks|thank you|appreciate|great': {
        response: "You're welcome! 😊 I'm glad I could help. If you have any more questions about UniPart, feel free to ask anytime!\n\nGood luck with your job search or posting! 🌟",
        quickReplies: ['Find Jobs', 'Post a Job', 'Main Menu']
    },
    'bye|goodbye|see you|exit': {
        response: "Goodbye! 👋 Thanks for using UniPart. Come back anytime you need help finding jobs or posting opportunities. Have a great day! 🌟",
        quickReplies: ['Find Jobs', 'Register', 'Contact Support']
    },
    
    // Default responses for unmatched queries
    'default': {
        response: "I'm not sure about that specific question. 🤔\n\nI can help you with:\n• Finding and applying for jobs\n• Posting job opportunities\n• Managing your profile\n• Understanding how UniPart works\n• Technical support\n\nWhat would you like to know?",
        quickReplies: ['Find Jobs', 'Post a Job', 'How to Register', 'Contact Support']
    }
};

// DOM Elements
const chatbotButton = document.getElementById('chatbotButton');
const chatbotContainer = document.getElementById('chatbotContainer');
const chatbotMessages = document.getElementById('chatbotMessages');
const quickRepliesContainer = document.getElementById('quickReplies');
const userInput = document.getElementById('userInput');
const sendButton = document.getElementById('sendButton');

// Toggle Chatbot
chatbotButton.addEventListener('click', () => {
    chatbotButton.classList.toggle('active');
    chatbotContainer.classList.toggle('active');
    
    if (chatbotContainer.classList.contains('active')) {
        userInput.focus();
        // Show welcome message on first open
        if (chatbotMessages.children.length === 0) {
            setTimeout(() => {
                addBotMessage(knowledgeBase['hello|hi|hey|greetings|hi there|good morning|good afternoon'].response);
                showQuickReplies(knowledgeBase['hello|hi|hey|greetings|hi there|good morning|good afternoon'].quickReplies);
            }, 300);
        }
    }
});

// Add Bot Message with Typing Effect
function addBotMessage(text) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message bot';
    messageDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="message-content"></div>
    `;
    chatbotMessages.appendChild(messageDiv);
    
    const messageContent = messageDiv.querySelector('.message-content');
    typeMessage(messageContent, text);
}

// Typing Animation Function
function typeMessage(element, text, speed = 10) {
    let index = 0;
    const formattedText = text.replace(/\n/g, '<br>');
    
    // Create a temporary div to parse HTML
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = formattedText;
    const fullText = tempDiv.innerHTML;
    
    element.innerHTML = '';
    
    // Add cursor
    const cursor = document.createElement('span');
    cursor.className = 'typing-cursor';
    
    function type() {
        if (index < fullText.length) {
            // Handle HTML tags
            if (fullText[index] === '<') {
                const closingTag = fullText.indexOf('>', index);
                if (closingTag !== -1) {
                    const htmlTag = fullText.substring(index, closingTag + 1);
                    // Remove cursor temporarily
                    if (cursor.parentNode) cursor.remove();
                    element.innerHTML += htmlTag;
                    element.appendChild(cursor);
                    index = closingTag + 1;
                }
            } else {
                // Remove cursor, add character, re-add cursor
                if (cursor.parentNode) cursor.remove();
                element.innerHTML += fullText[index];
                element.appendChild(cursor);
                index++;
            }
            
            scrollToBottom();
            setTimeout(type, speed);
        } else {
            // Remove cursor when done
            if (cursor.parentNode) cursor.remove();
            
            // Enable input after typing is complete
            userInput.disabled = false;
            sendButton.disabled = false;
        }
    }
    
    // Disable input while typing
    userInput.disabled = true;
    sendButton.disabled = true;
    
    // Add initial cursor
    element.appendChild(cursor);
    
    type();
}

// Add User Message
function addUserMessage(text) {
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message user';
    messageDiv.innerHTML = `
        <div class="message-content">${text}</div>
        <div class="message-avatar">
            <i class="fas fa-user"></i>
        </div>
    `;
    chatbotMessages.appendChild(messageDiv);
    scrollToBottom();
}

// Show Typing Indicator
function showTypingIndicator() {
    const typingDiv = document.createElement('div');
    typingDiv.className = 'message bot';
    typingDiv.innerHTML = `
        <div class="message-avatar">
            <i class="fas fa-robot"></i>
        </div>
        <div class="typing-indicator active">
            <span></span><span></span><span></span>
        </div>
    `;
    typingDiv.id = 'typing-indicator';
    chatbotMessages.appendChild(typingDiv);
    scrollToBottom();
}

function removeTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

// Show Quick Replies
function showQuickReplies(replies) {
    quickRepliesContainer.innerHTML = '';
    if (replies && replies.length > 0) {
        replies.forEach(reply => {
            const button = document.createElement('button');
            button.className = 'quick-reply-btn';
            button.textContent = reply;
            button.addEventListener('click', () => {
                handleUserMessage(reply);
            });
            quickRepliesContainer.appendChild(button);
        });
    }
}

// Find Best Match in Knowledge Base
function findBestMatch(userMessage) {
    const normalizedMessage = userMessage.toLowerCase().trim();
    
    for (const [pattern, data] of Object.entries(knowledgeBase)) {
        if (pattern === 'default') continue;
        
        const keywords = pattern.split('|');
        const matched = keywords.some(keyword => 
            normalizedMessage.includes(keyword.toLowerCase())
        );
        
        if (matched) {
            return data;
        }
    }
    
    return knowledgeBase['default'];
}

// Handle User Message
function handleUserMessage(message) {
    if (!message.trim()) return;
    
    // Add user message
    addUserMessage(message);
    userInput.value = '';
    
    // Show typing indicator
    showTypingIndicator();
    
    // Simulate thinking time (1-2 seconds for realism)
    const thinkingTime = 1000 + Math.random() * 1000; // Random between 1-2 seconds
    
    setTimeout(() => {
        removeTypingIndicator();
        
        // Find and add bot response with typing effect
        const matchedResponse = findBestMatch(message);
        addBotMessage(matchedResponse.response);
        
        // Show quick replies after message is fully typed
        const messageLength = matchedResponse.response.length;
        const typingDuration = messageLength * 30; // 30ms per character
        
        setTimeout(() => {
            showQuickReplies(matchedResponse.quickReplies);
        }, typingDuration + 200);
    }, thinkingTime);
}

// Send Message
sendButton.addEventListener('click', () => {
    handleUserMessage(userInput.value);
});

userInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        handleUserMessage(userInput.value);
    }
});

// Scroll to Bottom
function scrollToBottom() {
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
}