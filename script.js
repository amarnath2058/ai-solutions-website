// Wait for HTML to fully load
document.addEventListener("DOMContentLoaded", function() {

    // ========== IMAGE SLIDER (Home Page) ==========
   const images = [
    "https://images.unsplash.com/photo-1677442136019-21780ecad995?w=600&h=400&fit=crop",
    "https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=600&h=400&fit=crop",
    "https://images.unsplash.com/photo-1555255707-c07966088b7b?w=600&h=400&fit=crop",
    "https://images.unsplash.com/photo-1531746790731-6c087fecd65a?w=600&h=400&fit=crop"
];

    let current = 0;
    const sliderImage = document.getElementById("sliderImage");
    const dots = document.querySelectorAll(".slider-dots span");

    if (sliderImage && dots.length > 0) {
        function changeImage() {
            current++;
            if (current >= images.length) {
                current = 0;
            }
            sliderImage.src = images[current];
            dots.forEach(dot => dot.classList.remove("active"));
            dots[current].classList.add("active");
        }
        setInterval(changeImage, 3000);
    }

    // ========== MODAL FUNCTIONS (Insight Page) ==========
    window.openModal = function(title, description) {
        const modal = document.getElementById('modal');
        const modalTitle = document.getElementById('modalTitle');
        const modalDesc = document.getElementById('modalDesc');
        
        if (modal && modalTitle && modalDesc) {
            modalTitle.innerHTML = title;
            modalDesc.innerHTML = description;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }

    window.closeModal = function() {
        const modal = document.getElementById('modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modal');
        if (event.target === modal) {
            window.closeModal();
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            window.closeModal();
        }
    });

    // ========== CHATBOT TOGGLE ==========
    const chatbotIcon = document.getElementById("chatbotIcon");
    const chatbotWindow = document.getElementById("chatbotWindow");
    const closeChat = document.getElementById("closeChat");

    if (chatbotIcon && chatbotWindow && closeChat) {
        chatbotIcon.addEventListener("click", function() {
            chatbotWindow.classList.add("open");
            console.log("Chatbot opened");
        });

        closeChat.addEventListener("click", function() {
            chatbotWindow.classList.remove("open");
            console.log("Chatbot closed");
        });
    }

    // ========== CHATBOT MESSAGES ==========
    const chatBody = document.getElementById("chatBody");
    const chatInput = document.getElementById("chatInput");
    const sendBtn = document.getElementById("sendBtn");

    if (chatBody && chatInput && sendBtn) {
        
        function addMessage(message, isUser) {
            const msgDiv = document.createElement("div");
            msgDiv.textContent = message;
            msgDiv.className = isUser ? "user-msg" : "bot-msg";
            chatBody.appendChild(msgDiv);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        function sendMessage() {
            const message = chatInput.value.trim();
            if (message === "") return;
            
            addMessage(message, true);
            chatInput.value = "";
            
            setTimeout(() => {
                let reply = "";
                const lowerMsg = message.toLowerCase();
                
                if (lowerMsg.includes("hello") || lowerMsg.includes("hi")) {
                    reply = "Hello! How can I help you today?";
                } 
                else if (lowerMsg.includes("What services do you offer?")) {
                    reply = "We offer AI Virtual Assistant, Prototyping Tool, Data Analytics, and Employee Experience solutions.";
                }
                else if (lowerMsg.includes("contact")) {
                    reply = "Email: info@aisolutions.com | Phone: +44 123456789";
                }
                else if (lowerMsg.includes("price") || lowerMsg.includes("cost")) {
                    reply = "Please contact our sales team for pricing details.";
                }
                else {
                    reply = "Thank you for your message! Our team will get back to you soon.";
                }
                
                addMessage(reply, false);
            }, 1000);
        }

        sendBtn.addEventListener("click", sendMessage);
        chatInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                sendMessage();
            }
              if (isOpen && elements.body.children.length === 0) {
                addMessage("👋 Welcome! How can I help you today?", false);
            }
        });
    }

});