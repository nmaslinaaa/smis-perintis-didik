<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Test - Pusat Tuisyen Perintis Didik</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f4f7fa;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #25D366;
            text-align: center;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            height: 100px;
            resize: vertical;
        }
        button {
            background-color: #25D366;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background-color: #128C7E;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📱 WhatsApp Notification Test</h1>

        <div class="info">
            <strong>Important:</strong> To receive WhatsApp messages, you need to join the Twilio WhatsApp sandbox first.
            Send "join <code>" to +14155238886 on WhatsApp to enable messaging.
        </div>

        <form id="whatsappTestForm">
            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" placeholder="e.g., +60123456789" required>
                <small>Format: +60XXXXXXXXX (Malaysia)</small>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Enter your test message here..." required>Hello from Pusat Tuisyen Perintis Didik! This is a test WhatsApp message. 🎉</textarea>
            </div>

            <button type="submit">Send Test Message</button>
            <button type="button" onclick="clearForm()">Clear</button>
        </form>

        <div id="result" class="result"></div>
    </div>

    <script>
        document.getElementById('whatsappTestForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                phone_number: formData.get('phone_number'),
                message: formData.get('message')
            };

            fetch('/whatsapp/test', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                const resultDiv = document.getElementById('result');
                resultDiv.style.display = 'block';

                if (data.success) {
                    resultDiv.className = 'result success';
                    resultDiv.innerHTML = `
                        <strong>✅ Success!</strong><br>
                        Message sent to: ${data.phone}<br>
                        Response: ${data.message}
                    `;
                } else {
                    resultDiv.className = 'result error';
                    resultDiv.innerHTML = `
                        <strong>❌ Error!</strong><br>
                        ${data.message}
                    `;
                }
            })
            .catch(error => {
                const resultDiv = document.getElementById('result');
                resultDiv.style.display = 'block';
                resultDiv.className = 'result error';
                resultDiv.innerHTML = `
                    <strong>❌ Error!</strong><br>
                    Failed to send message: ${error.message}
                `;
            });
        });

        function clearForm() {
            document.getElementById('whatsappTestForm').reset();
            document.getElementById('result').style.display = 'none';
        }
    </script>
</body>
</html>
