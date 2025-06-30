<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title; ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 4rem auto;
            background-color: #fff;
            padding: 2rem 2.5rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        h1 {
            color: #4267B2;
            margin-bottom: 1rem;
        }

        p {
            line-height: 1.7;
            margin: 1rem 0;
        }

        ol {
            margin-left: 1.2rem;
        }

        .email {
            color: #0073e6;
            font-weight: bold;
        }

        footer {
            text-align: center;
            font-size: 0.9rem;
            margin-top: 2rem;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>User Data Deletion Instructions</h1>
        <p>
            In compliance with Facebook Platform rules, we provide a simple process for users who wish to delete their data collected through our application.
        </p>
        <h2>How to Request Deletion</h2>
        <p>
            If you have used Facebook Login to access our app and wish to delete your data, please follow the steps below:
        </p>
        <ol>
            <li>Go to your Facebook account settings.</li>
            <li>Navigate to <strong>Apps and Websites</strong>.</li>
            <li>Find our app in the list and click <strong>Remove</strong>.</li>
        </ol>

        <p>
            Alternatively, you may request data deletion directly by emailing us at: <br>
            <span class="email">createmore@dti.gov.ph</span>
        </p>

        <p>
            Please include your Facebook User ID in the message. Once we receive your request, we will delete your data from our records within 7 business days and notify you upon completion.
        </p>

    </div>
</body>

</html>