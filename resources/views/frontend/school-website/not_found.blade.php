 <style>
  .message-container {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            width: 100%;
        }
        .message-container h1 {
            color: #dc3545; /* Red color for the error message */
            font-size: 2.5rem;
            margin-bottom: 20px;
        }
        .message-container p {
            font-size: 1.2rem;
            color: #6c757d; /* Dark gray color for the description */
        }
 </style>
 <div class="message-container">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <a href="{{ url('result') }}" class="btn btn-danger btn-lg btn-back">Go Back</a>
    </div>