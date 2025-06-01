<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    
    $to = "koganyon7@email.com, friend@email.com"; // יש לשנות את זה!

    $subject = "הודעה חדשה מהאתר - קובץ STL";
    $body = "שם: $name\nאימייל: $email\n\nהודעה:\n$message";

    $file_attached = false;
    if (isset($_FILES['stlFile']) && $_FILES['stlFile']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['stlFile'];
        $file_path = $file['tmp_name'];
        $file_name = $file['name'];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $file_attached = true;
    }

    $boundary = md5(time());
    $headers = "From: $email\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $messageBody = "--$boundary\r\n";
    $messageBody .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $messageBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $messageBody .= $body . "\r\n";

    if ($file_attached) {
        $file_content = chunk_split(base64_encode(file_get_contents($file_path)));
        $messageBody .= "--$boundary\r\n";
        $messageBody .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $messageBody .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $messageBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $messageBody .= $file_content . "\r\n";
    }
    $messageBody .= "--$boundary--";

    if (mail($to, $subject, $messageBody, $headers)) {
        $form_status_message = "ההודעה נשלחה בהצלחה!";
        $form_status_class = "success";
    } else {
        $form_status_message = "אירעה שגיאה בשליחת ההודעה.";
        $form_status_class = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="he" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ברוכים הבאים</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
            direction: rtl;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
        }
        main {
            padding: 20px;
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        footer {
            background-color: #ddd;
            padding: 10px;
            margin-top: 20px;
            color: #555;
        }
        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
            text-align: right;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            text-align: right;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        input[type="file"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 25px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-status {
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
            font-weight: bold;
            text-align: center;
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
    </style>
</head>
<body>
    <header>
        <h1>ברוכים הבאים לאתר של יונתן ושל כרמל</h1>
    </header>
    <main>
        <h2>תוכן ראשי</h2>
        <p>פה תוכלו לקנות מה שבא לכם עד 10 סנטימטר בתלת מימד ולבוא לאסוף.</p>

        <h2>צור קשר</h2>

        <?php if (isset($form_status_message)): ?>
            <div class='form-status <?php echo $form_status_class; ?>'><?php echo $form_status_message; ?></div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">שם:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">אימייל:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">הודעה:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <label for="stlFile">העלה קובץ STL:</label>
            <input type="file" id="stlFile" name="stlFile" accept=".stl" required>

            <button type="submit">שלח</button>
        </form>
    </main>
    <footer>
        <p>כל הזכויות שמורות &copy; 2025</p>
    </footer>
</body>
</html>
