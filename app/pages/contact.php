<?php require page('includes/header')?>

<div class="center-content">
    <!-- Contact Info Section -->
    <div class="contact-info">
        <center><h2>Contact us here on this number: +977-9804563427</h2></center>
    </div>

    <!-- Friendly Greeting -->
    <center>
        <div class="smiley">😊</div>
        <div class="good-day"><b>"Have a Great taste in music, you’re in the right place!"</b></div>
    </center>

    <div class="contact-form">
        <h3>Get In Touch</h3>
        <p>If you have any questions, feedback, or need help, feel free to drop us a message using the contact form below:</p>
        <form action="send_message.php" method="POST">
            <div class="form-field">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name">
            </div>
            <div class="form-field">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email address">
            </div>
            <div class="form-field">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" required placeholder="Write your message here"></textarea>
            </div>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
    <!-- Added content ends here -->
</div>

<?php require page('includes/footer')?>
<style>
.about-us {
    background-color: #f9f9f9;
    padding: 20px;
    margin-top: 30px;
    border-radius: 8px;
}

.contact-form {
    background-color: #715764;
    color: white;
    padding: 20px;
    margin-top: 30px;
    border-radius: 8px;
}

.contact-form h3 {
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.contact-form .form-field {
    margin-bottom: 15px;
}

.contact-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.contact-form input, .contact-form textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.contact-form button.submit-btn {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.contact-form button.submit-btn:hover {
    background-color: #444;
}
</style>