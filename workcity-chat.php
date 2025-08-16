<?php
/**
 * Plugin Name: Workcity Chat
 * Description: Simple real-time chat widget connected to Node.js backend.
 * Version: 1.0
 * Author: Judgevan
 */

// Register shortcode [workcity_chat]
function workcity_chat_shortcode() {
    ob_start(); ?>
    
    <div id="chat-box" style="border:1px solid #ccc; padding:10px; width:300px; height:400px; overflow-y:auto;">
        <div id="messages"></div>
        <input id="chat-input" type="text" placeholder="Type a message..." style="width:80%;" />
        <button id="send-btn">Send</button>
    </div>

    <!-- Load Socket.IO client --><script src="http://localhost:4000/socket.io/socket.io.js"></script>
<script>
    const socket = io("http://localhost:4000"); 

    // Use WordPress logged-in username if available
    const wpUser = "<?php echo wp_get_current_user()->user_login ?: ''; ?>";
    const chatUser = wpUser !== "" ? wpUser : "Guest" + Math.floor(Math.random() * 1000);

    console.log("🔄 Connecting as:", chatUser);

    socket.on("connect", () => {
        console.log("✅ Connected to chat server:", socket.id);
    });

    socket.on("chat message", (data) => {
        const messagesDiv = document.getElementById("messages");
        const p = document.createElement("p");
        p.textContent = `${data.user}: ${data.text}`;
        messagesDiv.appendChild(p);
    });

    document.getElementById("send-btn").addEventListener("click", () => {
        const input = document.getElementById("chat-input");
        const message = input.value;
        if(message.trim() !== ""){
            socket.emit("chat message", { user: chatUser, text: message });
            input.value = "";
        }
    });
</script>

    
    <?php
    return ob_get_clean();
}
add_shortcode('workcity_chat', 'workcity_chat_shortcode');
