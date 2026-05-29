<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    
    <title>Chatbot</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">



 

    <!-- Custom CSS -->
    <style>
    
   
        body {
            font-family: 'Roboto', sans-serif; /* Default font for non-Persian */
            background-color: #212121;
            color:#fff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden; /* Prevents scrolling */
        }
        

        .main-container {
            display: flex;
            width: 100%; /* Full width */
            height: 100%;
        }

        /* Left Column */
        .left-column {
            width: 30%;
            padding: 20px;
            background-color: #171717;
            display: flex;
            flex-direction: column;
            position: relative;
            height: 100vh;
            box-sizing: border-box;
        }

        /* Combined Middle and Right Column */
        .chat-container {
            width: 83.33%; /* 5/6 of the screen */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            box-sizing: border-box; /* Includes padding in width */
        }

        .chat-window {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 20px;
            border-radius: 8px;
            padding: 15px;
            
            height: calc(100% - 100px); /* Dynamic height */
        }

        .chat-message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
            max-width: 75%;
            word-wrap: break-word;
        }

        @media screen and (max-width: 480px) {
            .chat-message {
                max-width: 100%; 
            }
        }

        .chat-message-bot {
    
    align-self: flex-start;
}

.chat-message-user {

     background-color: #303030;
    
     align-self: flex-end;
}

        /* Style for bot message container */
        .bot-message-container {
            flex-direction: column;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        /* Style for the Copy button */
        .copy-button {
            margin-top: 5px;
            background-color: #e9ecef;
            border: none;
            color: #333;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .copy-button:hover {
            background-color: #ccc;
        }

        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center; /* center textarea vertically */
            background-color: #303030;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 16px 68px 16px 15px; /* symmetric vertical padding */
            width: 90%;
            box-sizing: border-box;
            min-height: 60px;
            max-height: 50vh;
            overflow: hidden;
            position: relative; /* for absolute send button */
        }

        #messageInput {
            width: 85%;
            min-height: 40px;
            max-height: 40vh;
            padding: 0 16px 0 12px; /* remove extra top/bottom to vertically center */
            border-radius: 5px;
            border: 0;
            background-color: transparent;
            color: white;
            font-size: 16px;
            line-height: 1.4;
            display: flex;
            align-items: center; /* align placeholder/content vertically */
            resize: none;
            overflow-y: auto;
            word-wrap: break-word;
            box-sizing: border-box;
        }

        #messageInput::-webkit-scrollbar {
            width: 6px;
        }

        #messageInput::-webkit-scrollbar-track {
            background: transparent;
        }

        #messageInput::-webkit-scrollbar-thumb {
            background: #666;
            border-radius: 3px;
        }

        #messageInput:focus {
            outline: none !important;
            box-shadow: none !important;
            border: 0 !important;
        }

        #sendButton {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #666;
            color: black;
            border: none;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            right: 16px; /* keep away from scrollbar */
            top: 50%;
            transform: translateY(-50%); /* center vertically */
        }

        #sendButton i {
            font-size: 20px;
        }

        @media screen and (max-width: 480px) {
            .left-column {
                position: fixed;
                top: 0;
                left: 0;
                width: 80%;
                max-width: 300px;
                height: 100%;
                background-color: #171717;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 2000; /* Higher z-index than input-container */
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            }

            .left-column.open {
                transform: translateX(0);
            }

            .input-container {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background-color: #212121;
                padding: 10px;
                z-index: 1000; /* Lower z-index than left-column */
            }

            /* Ensure hamburger menu stays on top */
            .hamburger-menu {
                z-index: 2001; /* Higher than left-column */
            }

            .chat-container {
                width: 100%;
                padding: 10px 20px;
                box-sizing: border-box;
            }

             .chat-window {
                width: 100%;
                margin-top: 80px; /* Increased margin from top */
                margin-bottom: 120px;
                padding: 20px; /* Increased padding */
                box-sizing: border-box;
                height: calc(100vh - 200px); /* Adjusted height calculation to account for top margin */
                overflow-y: auto;
                position: relative;
            }

            .input-container {
                width: 90%;
                padding: 15px;
                margin: 0 auto 20px; /* Added margin bottom */
                box-sizing: border-box;
                position: fixed;
                bottom: 20px; /* Moved up from bottom */
                left: 50%;
                transform: translateX(-50%); /* Center horizontally */
                background-color: #303030;
                z-index: 1000;
                border-radius: 15px;
                box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
            }

            .chat-container {
                width: 100%;
                padding: 10px 20px;
                padding-bottom: 70px; /* Added padding to prevent content from being hidden */
                position: relative;
            }

            /* Ensure content doesn't get hidden behind the input container */
            .chat-message {
                margin-bottom: 20px; /* Add margin to the bottom of each message */
            }
        }

        @media screen and (max-width: 480px) and (-webkit-min-device-pixel-ratio: 2) {
            .input-container {
    margin: 30px auto; /* Adjust margin specifically for iOS */
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #303030;
    box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 15px;
    padding: 15px;

    box-sizing: border-box;
                
            }
        }

        .main-container {
            display: flex;
            height: 100vh;
        }

        .left-column {
            width: 30%;
            display: flex;
            flex-direction: column;
        }



            #messageInput {
                width: 80%;
                font-size: 16px;
                padding: 12px;
                max-height: 40vh;
                overflow-y: hidden;
            }

            #sendButton {
                flex-shrink: 0;
                margin-left: 10px;
                padding: 12px;
                font-size: 20px;
            }
        }

        /* Enhanced fade-in animation for both message and button */
        @keyframes smoothFadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.95);
            }
            50% {
                opacity: 0.5;
                transform: translateY(10px) scale(1.02);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Apply the same animation to bot message and the button */
        .smooth-fade-in {
            animation: smoothFadeIn 0.8s cubic-bezier(0.25, 0.1, 0.25, 1);
        }

        /* Style for generated images */
        .generated-image {
            max-width: 100%; /* Ensures the image doesn't exceed the container width */
            height: auto; /* Maintains aspect ratio */
            border-radius: 5px; /* Optional: rounded corners for aesthetics */
            display: block; /* Ensures proper alignment */
            margin: 10px auto; /* Centers the image */
        }

        /* Mobile-specific adjustments (if needed) */
        @media (max-width: 768px) {
            .image-container {
                max-width: 90%; /* Prevents overflow in smaller screens */
                margin: 5px auto; /* Centers the image container */
            }
        }
        
   


     
        
    .svg-icon {
    width: 25px; /* Adjust the size as needed */
    height: 25px;
    fill: white; /* This works if the SVG supports fill property */
    transition: transform 0.2s ease; /* Optional: Adds hover effect */
}

.svg-icon:hover {
    transform: scale(1.1); /* Optional: Slight zoom on hover */
}




    .conversation-item{
    
            margin-bottom:20px;
    }
       
       
       
     
        
#newChatLink {
   
    padding:20px;
    right: 10px; /* Adjust spacing from the right edge */
    top: 10px; /* Adjust spacing from the top edge */
    cursor: pointer;
}

#newChatLink img.svg-icon {
    width: 80%; /* Make the image fit the container */
    height: auto;
}

.new-chat-container {
    position: relative; /* Ensures #newChatLink is positioned relative to this container */
    height: 50px; /* Optional: Define the height of the container */
    margin-bottom: 20px; /* Space below the container */
}





    /* Styling the scrollbar for WebKit browsers (Chrome, Edge, Safari) */
    #chatWindow::-webkit-scrollbar,
    .conversation-list-container::-webkit-scrollbar {
        width: 12px; /* Width of the scrollbar */
    }

    #chatWindow::-webkit-scrollbar-track,
    .conversation-list-container::-webkit-scrollbar-track {
        background: #1a1a1a; /* Dark background for the track */
    }

    #chatWindow::-webkit-scrollbar-thumb,
    .conversation-list-container::-webkit-scrollbar-thumb {
        background: #444; /* Darker color for the thumb */
        border-radius: 6px; /* Rounded edges for the thumb */
    }

    #chatWindow::-webkit-scrollbar-thumb:hover,
    .conversation-list-container::-webkit-scrollbar-thumb:hover {
        background: #555; /* Slightly lighter color on hover */
    }

    /* Styling for Firefox */
    #chatWindow,
    .conversation-list-container {
        scrollbar-color: #444 #1a1a1a; /* Thumb color, Track color */
        scrollbar-width: thin; /* Makes the scrollbar thinner */
        max-height: 800px; /* Adjust as needed */
    overflow-y: auto; /* Enable vertical scrolling */
    }
    
    
    

         
 /* Hamburger Menu Styles */
        .hamburger-menu {
            position: fixed;
            top: 20px; /* Consistent spacing from top */
            left: 20px;
            z-index: 2001;
        }

        .hamburger-menu i {
            font-size: 24px;
        }

        @media screen and (max-width: 480px) {
            .hamburger-menu {
                display: block;
            }

            .left-column {
                position: fixed;
                top: 0;
                left: 0;
                width: 80%;
                max-width: 300px;
                height: 100%;
                background-color: #303030;
                color: white;
                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
         
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
            }

            .left-column.open {
                transform: translateX(0);
                
            }

            .chat-container {
               
                width: 100%;
                padding: 10px 20px;
            }
        }

        @media screen and (min-width: 768px) {
            .hamburger-menu {
                display: none;
            }

            .left-column {
             
                position: static;
                width: 30%;
                transform: translateX(0);
            }
        }



@media screen and (max-width: 480px) {
.left-column {
  width: 100%;
 

}
}


 @media screen and (min-width: 768px) {
.placeholder-text {
        color: #999;
        font-size: 30px;
        text-align: center;
        margin-top: 20px; /* Reduced from 10px to create space between logo and text */
        padding-right: 270px;
    }
    
.chat-logo {
        display: block;
        margin: 0 auto; /* Center the logo */
        max-width: 200px; /* Increase size for better visibility */
        height: auto;
        margin-top: 200px; /* Adjust spacing above logo */
        padding-right: 270px;
    }    
 }
 @media screen and (max-width: 480px) {
.placeholder-text {
        color: #999;
        font-size: 30px;
        text-align: center;
        margin-top: 20px; /* Reduced from 30px to create space between logo and text */
        padding-right: 0; /* Remove padding on mobile */
    }
    
    .chat-logo {
        display: block;
        margin: 0 auto; /* Center the logo */
        max-width: 150px; /* Slightly smaller size for small screens */
        height: auto;
        margin-top: 120px; /* Adjust spacing above logo */
        padding-right: 0; /* Remove padding on mobile */
    }



/* Fix the input container at the bottom - keep flexible height */
.input-container {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    padding: 10px 15px;
}

/* Ensure the imageResult container styles */
#imageResult {
    width: 100%;
    max-width: 600px; /* Adjust as needed */
    margin: 20px auto; /* Center the image */
    text-align: center; /* Center the image */
}

.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 3000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #303030;
    padding: 25px;
    border-radius: 12px;
    width: 320px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-header h3 {
    color: #ffffff;
    font-size: 20px;
    margin: 0 0 15px 0;
    text-align: center;
}

.modal-body p {
    color: #ffffff;
    font-size: 16px;
    margin: 0 0 25px 0;
    text-align: center;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.modal-buttons button {
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cancel-button {
    background-color: #424242;
    color: #ffffff;
}

.cancel-button:hover {
    background-color: #505050;
}

.confirm-delete-button {
    background-color: #dc3545;
    color: #ffffff;
}

.confirm-delete-button:hover {
    background-color: #c82333;
}

    </style>
</head>

<body>
          <div class="hamburger-menu" id="hamburgerMenu">
        <i class="fas fa-bars"></i>
    </div>

    <div class="main-container">

        <div class="left-column" id="leftColumn">
            <div class="new-chat-container">
                <button id="newChatLink" class="new-chat-button">
                    <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                        <path d="M15.6729 3.91287C16.8918 2.69392 18.8682 2.69392 20.0871 3.91287C21.3061 5.13182 21.3061 7.10813 20.0871 8.32708L14.1499 14.2643C13.3849 15.0293 12.3925 15.5255 11.3215 15.6785L9.14142 15.9899C8.82983 16.0344 8.51546 15.9297 8.29289 15.7071C8.07033 15.4845 7.96554 15.1701 8.01005 14.8586L8.32149 12.6785C8.47449 11.6075 8.97072 10.615 9.7357 9.85006L15.6729 3.91287ZM18.6729 5.32708C18.235 4.88918 17.525 4.88918 17.0871 5.32708L11.1499 11.2643C10.6909 11.7233 10.3932 12.3187 10.3014 12.9613L10.1785 13.8215L11.0386 13.6986C11.6812 13.6068 12.2767 13.3091 12.7357 12.8501L18.6729 6.91287C19.1108 6.47497 19.1108 5.76499 18.6729 5.32708ZM11 3.99929C11.0004 4.55157 10.5531 4.99963 10.0008 5.00007C9.00227 5.00084 8.29769 5.00827 7.74651 5.06064C7.20685 5.11191 6.88488 5.20117 6.63803 5.32695C6.07354 5.61457 5.6146 6.07351 5.32698 6.63799C5.19279 6.90135 5.10062 7.24904 5.05118 7.8542C5.00078 8.47105 5 9.26336 5 10.4V13.6C5 14.7366 5.00078 15.5289 5.05118 16.1457C5.10062 16.7509 5.19279 17.0986 5.32698 17.3619C5.6146 17.9264 6.07354 18.3854 6.63803 18.673C6.90138 18.8072 7.24907 18.8993 7.85424 18.9488C8.47108 18.9992 9.26339 19 10.4 19H13.6C14.7366 19 15.5289 18.9992 16.1458 18.9488C16.7509 18.8993 17.0986 18.8072 17.362 18.673C17.9265 18.3854 18.3854 17.9264 18.673 17.3619C18.7988 17.1151 18.8881 16.7931 18.9393 16.2535C18.9917 15.7023 18.9991 14.9977 18.9999 13.9992C19.0003 13.4469 19.4484 12.9995 20.0007 13C20.553 13.0004 21.0003 13.4485 20.9999 14.0007C20.9991 14.9789 20.9932 15.7808 20.9304 16.4426C20.8664 17.116 20.7385 17.7136 20.455 18.2699C19.9757 19.2107 19.2108 19.9756 18.27 20.455C17.6777 20.7568 17.0375 20.8826 16.3086 20.9421C15.6008 21 14.7266 21 13.6428 21H10.3572C9.27339 21 8.39925 21 7.69138 20.9421C6.96253 20.8826 6.32234 20.7568 5.73005 20.455C4.78924 19.9756 4.02433 19.2107 3.54497 18.2699C3.24318 17.6776 3.11737 17.0374 3.05782 16.3086C2.99998 15.6007 2.99999 14.7266 3 13.6428V10.3572C2.99999 9.27337 2.99998 8.39922 3.05782 7.69134C3.11737 6.96249 3.24318 6.3223 3.54497 5.73001C4.02433 4.7892 4.78924 4.0243 5.73005 3.54493C6.28633 3.26149 6.88399 3.13358 7.55735 3.06961C8.21919 3.00673 9.02103 3.00083 9.99922 3.00007C10.5515 2.99964 10.9996 3.447 11 3.99929Z"></path>
                    </svg>
                    New Chat
                </button>
            </div>
            
            <div id="conversationListContainer" class="conversation-list-container">
                <div id="conversationList"></div>
            </div>
            <div class="quick-actions">
                @php
                    $userName = isset($user) ? ($user['name'] ?? Session::get('name')) : Session::get('name');
                    $userEmail = isset($user) ? ($user['email'] ?? Session::get('email')) : Session::get('email');
                    $displayName = $userName ?: ($userEmail ?: 'Account');
                @endphp
                <button class="quick-actions-toggle" id="quickActionsToggle">
                    <i class="fas fa-user"></i>
                    {{ $displayName }}
                </button>
                <div class="quick-actions-menu" id="quickActionsMenu">
                    <a href="/dashboard" class="quick-action-item">
                        <i class="fas fa-th-large"></i>
                        Dashboard
                    </a>
                    <form action="/logout" method="POST" id="quickActionsLogoutForm" class="quick-action-item quick-action-form">
                        @csrf
                        <button type="submit">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                    <a href="{{ route('upgrade-plan') }}" class="quick-action-item">
                        <i class="fas fa-crown" style="color:#ffd700;"></i>
                        Upgrade Plan
                    </a>
                </div>
            </div>
        </div>




        <div class="chat-container">
            <div id="chatWindow" class="chat-window">
            </div>

            <div class="input-container" id="composerContainer">
    <textarea id="messageInput" placeholder="Ask Me..." rows="1"></textarea>
    <button id="sendButton"><i class="fas fa-arrow-up"></i></button>
</div>
<div id="imageResult"></div>
    
</div>





        </div>
    </div>
    <style>
        /* Add this new style for loading icon */
        .loading-spinner {
            display: none;
            width: 25px;
            height: 25px;
            margin: 10px 0;
            border: 2px solid #303030;
            border-top: 2px solid #ccc;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            align-self: flex-start;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .main-container {
            display: flex;
            height: 100vh;
        }

        /* Add this new style for thinking message */
        .thinking-message {
            background-color: #303030;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 10px 0;
            font-size: 14px;
            display: none;
            width: fit-content;
            align-self: flex-start;
        }

        .new-chat-container {
            padding: 10px;
            text-align: center;
        }

        .conversation-list-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .conversation-item {
            position: relative; /* Position relative to allow absolute positioning of the menu */
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            cursor: pointer;
            display: flex; /* Use flexbox for layout */
            justify-content: space-between; /* Space between text and menu */
            align-items: center; /* Center items vertically */
        }

    
          .conversation-item-container:hover{
            background-color: #303030;
        }



        .chat-container {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        .chat-window {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
           
        }

        .input-container {
            display: flex;
            justify-content: space-between;
            align-items: center; /* vertically center children */
            position: relative; /* anchor send button */
            background-color: #303030;
            border-radius: 15px;
            padding: 16px 68px 16px 15px; /* symmetric vertical padding */
            width: 90%;
            box-sizing: border-box;
            min-height: 60px;
            max-height: 50vh;
            overflow: hidden;
        }

        #messageInput {
            width: 100%;
            padding: 10px 16px 10px 12px; /* internal padding only */
            border-radius: 5px;
            margin-right: 0;
            max-height: 40vh;
            overflow-y: auto;
        }

        #sendButton {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
    .logout-container {
        position: fixed;
        top: 20px;
        right: 40px;
        z-index: 1000;
    }

    .logout-button {
         
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
    @media screen and (max-width: 480px) {
      .logout-button {
         
        background-color: #dc3545;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }
}
    .logout-button:hover {
        background-color: #c82333;
    }

    .logout-button i {
        font-size: 16px;
    }

        
                .new-chat-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .new-chat-button {
            display: flex;
            align-items: center;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .new-chat-button:hover {
            background-color:#f13648;
        }

        .svg-icon {
            width: 25px;
            height: 25px;
            margin-right: 10px;
        }

        .svg-icon path {
            fill: #fff;
        }
        
        
        
        
        
        .upgrade-plan-button {

    padding: 14px;
    font-size: 15px;
    color: #fff;
    border-radius: 8px;
    cursor: pointer;
    text-align: center;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    z-index: 10;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

/* Quick Actions (replaces Upgrade Plan button) */
.quick-actions {
    padding: 10px;
}

.quick-actions-toggle {
    width: 100%;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 12px 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.quick-actions-toggle:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.quick-actions-menu {
    display: none;
    flex-direction: column;
    gap: 8px;
    margin-top: 10px;
    background-color: #303030;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 10px;
}

.quick-actions-menu.show {
    display: flex;
}

.quick-action-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #fff;
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.quick-action-item:hover {
    background-color: #404040;
}

.quick-action-form {
    margin: 0;
}

.quick-action-form button {
    width: 100%;
    background: transparent;
    border: none;
    color: #fff;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0;
    cursor: pointer;
}

.upgrade-plan-button i {
    color: #ffd700;
    font-size: 16px;
    transition: transform 0.3s ease;
}

.upgrade-plan-button:hover {
    transform: translateY(-2px);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.upgrade-plan-button:hover i {
    transform: rotate(20deg);
}

.upgrade-plan-button:active {
    transform: translateY(1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

/* ================== */
/* Mobile Media Query */
/* ================== */
@media screen and (max-width: 480px) {
    .left-column {
        width: 100%;
    }

    .quick-actions {
        padding: 12px 15px;
    }
}

/* ================== */
/* Desktop Media Query */
/* ================== */
@media screen and (min-width: 768px) {
    .chat-logo {
        margin: 0 auto;
        max-width: 200px;
        height: auto;
        margin-top: 200px;
    }
    .placeholder-text {
        font-size: 30px;
        margin-top: 20px;
        padding-right: 270px;
    }
}

   
   
   
   /* Hide the existing logout button on mobile */
@media screen and (max-width: 480px) {
    .logout-button {
        display: none;
    }

    /* Show the shutdown button on mobile */
    .shutdown-button {
        display: block;
        position: fixed;
        top: 20px; /* Consistent spacing from top */
        right: 20px;
        z-index: 2001;
        width: 50px;
        height: 50px;
        background-color: #dc3545;
        border: none;
        border-radius: 50%;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .shutdown-button:hover {
        background-color: #c82333;
        transform: scale(1.05);
    }

    .shutdown-button i {
        font-size: 24px;
    }
}

/* Hide the shutdown button on larger screens */
@media screen and (min-width: 481px) {
    .shutdown-button {
        display: none;
    }
}








.conversation-item {
    position: relative; /* Ensure relative positioning for absolute child elements */
    padding: 10px;
    margin-bottom: 5px;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.menu-icon {
    cursor: pointer;
    position: relative;
    visibility: hidden; /* Hide by default */
    padding: 20px;
}

.conversation-item-container:hover .menu-icon {
    visibility: visible; /* Show on hover */
}

.vertical-menu {
  position: absolute;
    top: 100%; /* Exactly below the menu-icon */
    right: -10px; /* Move slightly to the right */
    background-color: #303030;
    z-index: 2000; /* Higher z-index to appear above left-column */
    width: 120px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    display: none; /* Initially hidden */
    border-radius: 10px;
}

.menu-item {
    padding: 0; /* Reset padding to avoid extra spacing */
    cursor: default; /* Container itself is not clickable */
}

.delete-button {
    width: 100%;
    background: transparent;
    border: none;
    padding: 10px;
    text-align: left;
    cursor: pointer;
    display: flex;
    align-items: center;
    border-radius:10px;
}

.delete-button:hover {
    background-color: #404040;
}


.custom-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 3000;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #303030;
    padding: 25px;
    border-radius: 12px;
    width: 320px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-header h3 {
    color: #ffffff;
    font-size: 20px;
    margin: 0 0 15px 0;
    text-align: center;
}

.modal-body p {
    color: #ffffff;
    font-size: 16px;
    margin: 0 0 25px 0;
    text-align: center;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.modal-buttons button {
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.cancel-button {
    background-color: #424242;
    color: #ffffff;
}

.cancel-button:hover {
    background-color: #505050;
}

.confirm-delete-button {
    background-color: #dc3545;
    color: #ffffff;
}

.confirm-delete-button:hover {
    background-color: #c82333;
}

    </style>

<script>
// Global variables and DOM elements
let chatWindow = null;
let messageInput = null;
let sendButton = null;
let newChatLink = null;
let conversationList = null;
let hamburgerMenu = null;
let leftColumn = null;
let placeholderText = null;
let logo = null;
let conversationUrl = null;
let currentDeletingUrl = null;

// Initialize all DOM elements and event listeners
function initializeChat() {
    // Initialize DOM elements
    chatWindow = document.getElementById('chatWindow');
    messageInput = document.getElementById('messageInput');
    sendButton = document.getElementById('sendButton');
    newChatLink = document.getElementById('newChatLink');
    conversationList = document.getElementById('conversationList');
    hamburgerMenu = document.getElementById('hamburgerMenu');
    leftColumn = document.getElementById('leftColumn');
    
    // Create logo element first
    logo = document.createElement('img');
    logo.src = '/image/LogoWeb.webp';
    logo.alt = 'Logo';
    logo.classList.add('chat-logo');

    // Create placeholder text element
    placeholderText = document.createElement('div');
    placeholderText.textContent = "How can I help you?";
    placeholderText.classList.add('placeholder-text');

    // Add elements to chat window in the correct order
    chatWindow.appendChild(logo);
    chatWindow.appendChild(placeholderText);

    // Initialize CSRF token
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const metaTag = document.createElement('meta');
        metaTag.setAttribute('name', 'csrf-token');
        metaTag.setAttribute('content', '{{ csrf_token() }}');
        document.head.appendChild(metaTag);
    }

    // Get conversation URL from query params
    conversationUrl = new URLSearchParams(window.location.search).get('conversation_url');

    // Add event listeners
    newChatLink.addEventListener('click', (e) => {
        e.preventDefault();
        leftColumn.classList.remove('open');
        generateNewConversation();
    });

    hamburgerMenu.addEventListener('click', () => {
        leftColumn.classList.toggle('open');
    });

    sendButton.addEventListener('click', sendMessage);
    
    messageInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
            return;
        }
        // Auto-resize on input
        setTimeout(() => {
            messageInput.style.height = 'auto';
            messageInput.style.height = Math.min(messageInput.scrollHeight, window.innerHeight * 0.4) + 'px';
        }, 0);
    });

    // Initial autosize and on input
    const autoSize = () => {
        const container = document.getElementById('composerContainer');
        if (!container) return;
        // Reset heights to recalc
        messageInput.style.height = 'auto';
        container.style.height = 'auto';
        const maxInput = Math.min(window.innerHeight * 0.4, 380);
        const inputH = Math.min(messageInput.scrollHeight, maxInput);
        messageInput.style.height = inputH + 'px';
        messageInput.style.overflowY = (messageInput.scrollHeight > inputH) ? 'auto' : 'hidden';
        // Container height fits content (textarea + paddings) but is capped by CSS max-height
        const computed = parseFloat(getComputedStyle(container).maxHeight);
        const desired = messageInput.offsetHeight + 24; // padding space
        container.style.height = Math.min(desired, isNaN(computed) ? desired : computed) + 'px';
    };
    messageInput.addEventListener('input', autoSize);
    autoSize();

    const logoutForm = document.getElementById('logoutForm');
    if (logoutForm) {
        logoutForm.addEventListener('submit', handleLogout);
    }

    // Initialize the chat
    loadConversation();
    loadConversationList();
}

// Function to show the placeholder message
function showPlaceholderMessage() {
    if (placeholderText && logo) {
        placeholderText.style.display = 'block';
        logo.style.display = 'block';
    }
}

// Function to hide the placeholder message
function hidePlaceholderMessage() {
    if (placeholderText && logo) {
        placeholderText.style.display = 'none';
        logo.style.display = 'none';
    }
}

// Handle logout form submission
function handleLogout(e) {
    e.preventDefault();
    
    fetch('/logout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
    })
    .then(response => {
        if (response.ok) {
            window.location.href = '/login';
        } else {
            throw new Error('Logout failed');
        }
    })
   
}

// Function to detect if a message is in Persian
function isPersian(text) {
    if (!text) return false;
    // Count dominant script rather than a single hit to avoid false RTL on mixed text
    const persianLetters = (text.match(/[\u0600-\u06FF]/g) || []).length;
    const latinLetters = (text.match(/[A-Za-z]/g) || []).length;
    const persianDigits = (text.match(/[\u06F0-\u06F9]/g) || []).length;
    const westernDigits = (text.match(/[0-9]/g) || []).length;
    const persianScore = persianLetters + persianDigits * 0.5;
    const latinScore = latinLetters + westernDigits * 0.2;
    return persianScore > latinScore * 1.1; // require clear dominance
}

function getTextDirection(text) {
    return isPersian(text) ? 'rtl' : 'ltr';
}

// Function to format bot responses
function formatBotResponse(content) {
    // First replace HTML line breaks with newlines
    let formatted = content.replace(/<br\s*\/?>(?!\n)/g, '\n');
    
    // Remove extra newlines between numbers and their text
    formatted = formatted.replace(/(\d+\.)\s*\n+\s*/g, '$1 ');
    
    // Add paragraph breaks after complete numbered items
    formatted = formatted.replace(/(\d+\..+?)\n/g, '$1\n\n');
    
    // Add paragraph breaks after question marks and exclamation marks
    formatted = formatted.replace(/([?!]) /g, '$1\n\n');
    
    // Add proper spacing after commas
    formatted = formatted.replace(/,([a-zA-Z])/g, ', $1');
    
    // Add proper spacing after periods if missing
    formatted = formatted.replace(/\.([a-zA-Z])/g, '. $1');

    if (!isPersian(formatted)) {
        // English-only formatting
        // 1) Convert Markdown headings (e.g., ### Title) to bold
        formatted = formatted.replace(/^#{1,6}\s*(.+)$/gm, '<strong>$1<\/strong>');

        // 2) Convert day schedule lines to h3 (e.g., "Day Seven: Rest day", "Day 5: Lower Body")
        formatted = formatted.replace(/^(Day\s+(?:[A-Za-z]+|\d+)[^\n]*?:.*)$/gmi, '<h3>$1<\/h3>');

        // 3) Convert lines wrapped in single or double asterisks to bold (e.g., *Practical Applications & Tips*)
        formatted = formatted.replace(/^\*{1,2}\s*([^\*\n][^\n]*?)\s*\*{1,2}\s*$/gm, '<strong>$1<\/strong>');
    } else {
        // Persian-specific formatting
        // 1) Convert Markdown headings (rare, but supported) to bold
        formatted = formatted.replace(/^#{1,6}\s*(.+)$/gm, '<strong>$1<\/strong>');

        // 2) Convert lines wrapped in asterisks to bold (e.g., *نکات مهم*)
        formatted = formatted.replace(/^\*{1,2}\s*([^\*\n][^\n]*?)\s*\*{1,2}\s*$/gm, '<strong>$1<\/strong>');

        // 3) Convert Persian section headers ending with colon to h3
        // Avoid list items: do not match lines starting with -, digits, bullets or asterisks
        formatted = formatted.replace(/^(?![\-\d\u06F0-\u06F9\u2022\*])([\u0600-\u06FF\sA-Za-z0-9‌]+?):\s*$/gm, '<h3>$1<\/h3>');

        // 4) Convert lines like "روز اول:" / "روز ۱:" to h3
        formatted = formatted.replace(/^\s*(روز\s+[\u0600-\u06FF0-9\u06F0-\u06F9]+[^\n]*?)\s*[:：]\s*$/gm, '<h3>$1<\/h3>');

        // 5) Bold the title part after Persian/Western numbered list markers (e.g., "۱. عنوان:" or "1. عنوان:")
        formatted = formatted.replace(/^\s*([0-9\u06F0-\u06F9]+)\.\s*([^:\n]+)(:?)\s*$/gm, '$1. <strong>$2<\/strong>$3');

        // 6) Bold any text inside parentheses throughout the message
        formatted = formatted.replace(/\(([^)]+)\)/g, '(<strong>$1<\/strong>)');

        // 7) Fallback: promote standalone Persian lines (not lists) to h3
        formatted = formatted.replace(/^(?![\-\d\u06F0-\u06F9\u2022\*])\s*([\u0600-\u06FF][\u0600-\u06FF\s]{2,})\s*$/gm, '<h3>$1<\/h3>');
    }
    
    // Trim extra whitespace and remove multiple consecutive newlines
    formatted = formatted.replace(/\n{3,}/g, '\n\n').trim();
    
    return formatted;
}

// Function to create a message element
function createMessageElement(content, role, type = 'text') {
    const messageElement = document.createElement('div');
    messageElement.classList.add('chat-message', role === 'user' ? 'chat-message-user' : 'chat-message-bot');

    const textContainer = document.createElement('div');
    textContainer.style.whiteSpace = 'pre-wrap';
    textContainer.style.wordBreak = 'break-word';

    if (role === 'bot') {
        const formattedContent = formatBotResponse(content);
        messageElement.innerHTML = formattedContent
            .replace(/\n/g, '<br>')
            .replace(/<br><br>/g, '<br><br style="margin-bottom: 10px;">');

        // Add DALL-E prompt if user asks about image generation
        if (content.toLowerCase().includes('image') || content.toLowerCase().includes('picture') || 
            content.toLowerCase().includes('photo') || content.toLowerCase().includes('عکس')) {
            const dallePrompt = document.createElement('div');
            dallePrompt.style.marginTop = '10px';
            dallePrompt.style.padding = '10px';
            dallePrompt.style.backgroundColor = '#303030';
            dallePrompt.style.borderRadius = '5px';
            dallePrompt.innerHTML = 'For image generation, please visit: <a href="https://ai.archeoam.com/dalle" target="_blank" style="color: #dc3545; ">Archeo Image Generator</a>';
            messageElement.appendChild(dallePrompt);
        }
    } else {
        messageElement.textContent = content;
    }

    // Handle RTL/LTR text based on dominant script
    const dir = getTextDirection(content);
    messageElement.style.direction = dir;
    messageElement.style.textAlign = dir === 'rtl' ? 'right' : 'left';

    return messageElement;
}

// Function to load the conversation
async function loadConversation() {
    if (!conversationUrl) {
        showPlaceholderMessage();
        return;
    }

    try {
        const response = await fetch(`/api/chatbot/get-messages?conversation_url=${encodeURIComponent(conversationUrl)}`);
        
        if (!response.ok) {
            throw new Error(`Failed to fetch messages: ${response.statusText}`);
        }

        const data = await response.json();
        if (!data.success) {
            throw new Error(data.error || 'Failed to load messages');
        }

        chatWindow.innerHTML = '';
        hidePlaceholderMessage();

        data.messages.forEach(message => {
            // Determine if the content is an image URL
            const isImage = message.type === 'image' || 
                          (message.content && message.content.includes('blob.core.windows.net'));
            
            const messageElement = createMessageElement(
                message.content,
                message.role === 'user' ? 'user' : 'bot',
                isImage ? 'image' : 'text'
            );
            
            chatWindow.appendChild(messageElement);
        });

        chatWindow.scrollTop = chatWindow.scrollHeight;
    } catch (error) {
        console.error('Error loading conversation:', error);
        displayErrorMessage("Failed to load conversation. Please try again.");
    }
}

// Function to setup conversation item clicks
function setupConversationItemClick() {
    const conversationItems = document.querySelectorAll('.conversation-item');
    conversationItems.forEach(item => {
        item.addEventListener('click', () => {
            leftColumn.classList.remove('open');
        });
    });
}

// Function to handle delete message action
function deleteMessage(conversationUrl) {
    showDeleteConfirmation(conversationUrl);
}

// Function to show delete confirmation modal
function showDeleteConfirmation(conversationUrl) {
    currentDeletingUrl = conversationUrl;
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'flex';
}

// Function to hide delete confirmation modal
function hideDeleteConfirmation() {
    const modal = document.getElementById('deleteConfirmModal');
    modal.style.display = 'none';
    currentDeletingUrl = null;
}

// Function to handle delete action with proper cleanup and redirect
async function handleDelete() {
    if (!currentDeletingUrl) return;
    
    try {
        const response = await fetch('/api/chatbot/delete-conversation', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                conversation_url: currentDeletingUrl
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Find and remove the conversation item from the list
            const itemContainer = document.querySelector(`.conversation-item-container`);
            if (itemContainer) {
                itemContainer.remove();
            }

            // If we're currently viewing the deleted conversation
            if (currentDeletingUrl === conversationUrl) {
                // Clear the current conversation URL
                conversationUrl = null;
                
                // Update the URL without the query parameter
                window.history.pushState({}, '', '/chatbot');
                
                // Clear chat window and show placeholder
                chatWindow.innerHTML = '';
                chatWindow.appendChild(logo);
                chatWindow.appendChild(placeholderText);
                showPlaceholderMessage();
            }

            // Refresh the conversation list
            await loadConversationList();
        }
    } catch (error) {
        console.error('Error deleting conversation:', error);
    } finally {
        hideDeleteConfirmation();
    }
}

// Function to load conversation list
async function loadConversationList() {
    try {
        const response = await fetch('/api/chatbot/get-conversations');
        const data = await response.json();

        if (data.success) {
            conversationList.innerHTML = '';
            data.conversations.forEach(conversation => {
                const itemContainer = document.createElement('div');
                itemContainer.classList.add('conversation-item-container');
                itemContainer.style.display = 'flex';
                itemContainer.style.justifyContent = 'space-between';
                itemContainer.style.alignItems = 'center';
                itemContainer.style.position = 'relative';
                itemContainer.style.borderRadius = '10px';

                // Set background color if this is the current conversation
                if (conversation.url === conversationUrl) {
                    itemContainer.style.backgroundColor = '#444';
                }

                // Conversation text element
                const conversationElement = document.createElement('div');
                conversationElement.classList.add('conversation-item');
                conversationElement.textContent = conversation.title;

                // Update click handler to the container
                itemContainer.addEventListener('click', () => {
                    // Remove background color from all containers
                    document.querySelectorAll('.conversation-item-container').forEach(container => {
                        container.style.backgroundColor = '';
                    });
                    // Set background color for clicked container
                    itemContainer.style.backgroundColor = '#444';
                    
                    conversationUrl = conversation.url;
                    window.history.pushState({}, '', `/chatbot?conversation_url=${encodeURIComponent(conversationUrl)}`);
                    loadConversation();
                    leftColumn.classList.remove('open');
                });

                // Three-dot menu icon
                const menuIcon = document.createElement('div');
                menuIcon.classList.add('menu-icon');
                menuIcon.textContent = '...';
                menuIcon.onclick = (event) => {
                    event.stopPropagation(); // Prevent triggering the item click
                    toggleMenu(event);
                };

                // Vertical menu
                const verticalMenu = document.createElement('div');
                verticalMenu.classList.add('vertical-menu');
                verticalMenu.style.display = 'none';

                // Create the menu-item container
                const menuItemContainer = document.createElement('div');
                menuItemContainer.classList.add('menu-item');

                // Create the delete button inside the container
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('delete-button');
                deleteButton.innerHTML = '<i class="fas fa-trash-alt" style="color: red; margin-right: 5px;"></i><span style="color: red;">Delete</span>';
                deleteButton.onclick = (event) => {
                    event.stopPropagation(); // Prevent triggering the item click
                    deleteMessage(conversation.url);
                };

                // Append the delete button to the menu-item container
                menuItemContainer.appendChild(deleteButton);

                // Append the menu-item container to the vertical menu
                verticalMenu.appendChild(menuItemContainer);

                // Append elements to container
                itemContainer.appendChild(conversationElement);
                itemContainer.appendChild(menuIcon);
                itemContainer.appendChild(verticalMenu);

                // Append container to conversation list
                conversationList.appendChild(itemContainer);
            });

            setupConversationItemClick();
        } else {
            console.error('Failed to load conversations:', data.error || 'Unknown error');
        }
    } catch (error) {
        console.error('Error loading conversation list:', error);
        displayErrorMessage("Failed to load conversation list. Please try again.");
    }
}

// Function to generate a new conversation
async function generateNewConversation() {
    try {
        chatWindow.innerHTML = '';
        chatWindow.appendChild(logo);
        chatWindow.appendChild(placeholderText);
        
        conversationUrl = null;
        window.history.pushState({}, '', '/chatbot');
        showPlaceholderMessage();
        
        await loadConversationList();
    } catch (error) {
        console.error('Error generating new conversation:', error);
        displayErrorMessage("Failed to start new conversation. Please try again.");
    }
}

// Function to generate a conversation URL
async function generateConversationUrl(message) {
    try {
        const response = await fetch("/api/chatbot/generate-url", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        if (data.success && data.conversation_url) {
            conversationUrl = data.conversation_url;
            window.history.pushState({}, "", `/chatbot?conversation_url=${encodeURIComponent(conversationUrl)}`);
            return data;
        } else {
            throw new Error(data.error || "Failed to generate conversation URL");
        }
    } catch (error) {
        console.error("Error generating conversation URL:", error);
        displayErrorMessage(error.message || "Failed to start new conversation. Please try again.");
        return null;
    }
}

// Function to send a message
async function sendMessage() {
    const userMessage = messageInput.value.trim();
    console.log('sendMessage called with:', userMessage, 'conversationUrl:', conversationUrl);
    if (!userMessage) return;

    messageInput.value = '';
    // reset height after sending
    messageInput.style.height = 'auto';
    hidePlaceholderMessage();

    try {
        if (!conversationUrl) {
            const urlData = await generateConversationUrl(userMessage);
            if (!urlData || !urlData.conversation_url) {
                throw new Error('Failed to generate conversation URL');
            }
            conversationUrl = urlData.conversation_url;
        }

        // Display user message
        const userMessageElement = createMessageElement(userMessage, 'user');
        chatWindow.appendChild(userMessageElement);

        // Create and show loading spinner
        const spinner = document.createElement('div');
        spinner.className = 'loading-spinner';
        spinner.style.display = 'block';
        chatWindow.appendChild(spinner);
        chatWindow.scrollTop = chatWindow.scrollHeight;

        // Send message to server
        const response = await fetch('/api/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: userMessage,
                conversation_url: conversationUrl
            })
        });

        if (!response.ok) {
            spinner.remove();
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        // Remove loading spinner
        spinner.remove();

        if (data.success && data.response) {
            const botMessageElement = createMessageElement(data.response, 'bot');
            chatWindow.appendChild(botMessageElement);
            chatWindow.scrollTop = chatWindow.scrollHeight;

            await saveMessage({
                role: 'user',
                content: userMessage,
                type: 'text'
            });
            await saveMessage({
                role: 'assistant',
                content: data.response,
                type: 'text'
            });

            await loadConversationList();
        } else {
            throw new Error(data.error || 'Unknown error');
        }
    } catch (error) {
        console.error('Error:', error);
        displayErrorMessage(error.message || "Failed to send message. Please try again.");
    }
}

// Function to save a message
async function saveMessage(message) {
    if (!conversationUrl) {
        console.error("conversationUrl is missing!");
        return;
    }

    try {
        const response = await fetch("/api/chatbot/save-message", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                conversation_url: conversationUrl,
                role: message.role,
                content: message.content,
                type: message.type || 'text'
            }),
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log("Message saved:", data);
    } catch (error) {
        console.error("Error saving message:", error);
    }
}

// Function to display an error message
function displayErrorMessage(message) {
    const errorElement = document.createElement('div');
    errorElement.className = 'chat-message chat-message-error';
    errorElement.style.color = '#ff4444';
    errorElement.style.backgroundColor = 'rgba(255, 0, 0, 0.1)';
    errorElement.textContent = message;
    chatWindow.appendChild(errorElement);
    chatWindow.scrollTop = chatWindow.scrollHeight;
}

// Initialize everything when the DOM is ready
document.addEventListener('DOMContentLoaded', initializeChat);

// Check for pending message from home page and create new conversation
document.addEventListener('DOMContentLoaded', function() {
    // Prefer URL, then sessionStorage, then localStorage
    const urlParams = new URLSearchParams(window.location.search);
    const urlMessage = urlParams.get('message');
    const urlCreate = urlParams.get('create_conversation') === 'true';
    const chatMessage = urlMessage || sessionStorage.getItem('chatMessage') || localStorage.getItem('pendingChatMessage');
    const createNewConversation = urlCreate || sessionStorage.getItem('createNewConversation') === 'true' || localStorage.getItem('pendingCreateConversation') === 'true';
    
    console.log('Checking for pending message:', chatMessage, createNewConversation);
    console.log('URL parameters:', window.location.search);
    console.log('sessionStorage chatMessage:', sessionStorage.getItem('chatMessage'));
    console.log('localStorage pendingChatMessage:', localStorage.getItem('pendingChatMessage'));
    
    if (chatMessage && createNewConversation) {

        
        // Clear the flags from sessionStorage and localStorage
        sessionStorage.removeItem('chatMessage');
        sessionStorage.removeItem('createNewConversation');
        localStorage.removeItem('pendingChatMessage');
        localStorage.removeItem('pendingCreateConversation');
        
        // Create new conversation and send message
        setTimeout(async () => {
            try {
                // Show loading state but do not render a visible "creating conversation" message
                hidePlaceholderMessage();
                let loadingDiv = null;

                // Generate new conversation URL using existing function
                console.log('Generating conversation URL for message:', chatMessage);
                const urlData = await generateConversationUrl(chatMessage);
                
                if (urlData && urlData.conversation_url) {
                    console.log('New conversation URL:', urlData.conversation_url);
                    
                    // Remove loading indicator
                    if (loadingDiv) loadingDiv.remove();
                    
                    // Send the message automatically
                    console.log('Sending message automatically:', chatMessage);
                    if (messageInput && chatWindow) {
                        messageInput.value = chatMessage;
                        // Wait a bit more to ensure everything is ready
                        setTimeout(() => {
                            console.log('About to call sendMessage, conversationUrl:', conversationUrl);
                            sendMessage();
                        }, 1000);
                    }
                } else {
                    console.error('Failed to create conversation');
                    if (loadingDiv) loadingDiv.remove();
                    // Fallback: just send the message normally
                    if (messageInput) {
                        messageInput.value = chatMessage;
                        sendMessage();
                    }
                }
            } catch (error) {
                console.error('Error creating conversation:', error);
                // Remove loading indicator
                if (loadingDiv) loadingDiv.remove();
                // Fallback: just send the message normally
                if (messageInput) {
                    messageInput.value = chatMessage;
                    sendMessage();
                }
            }
        }, 2000); // Increased wait time to ensure chat is fully initialized
    }
});

// Add this near the top of your script, with other initialization code
document.addEventListener('DOMContentLoaded', function() {
    // Existing initialization code...
    
    // Quick Actions dropdown toggle
    const quickToggle = document.getElementById('quickActionsToggle');
    const quickMenu = document.getElementById('quickActionsMenu');
    if (quickToggle && quickMenu) {
        quickToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            quickMenu.classList.toggle('show');
        });
        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!quickMenu.contains(e.target) && e.target !== quickToggle) {
                quickMenu.classList.remove('show');
            }
        });
    }

    // Add event listener for the shutdown button
    const shutdownButton = document.getElementById('shutdownButton');
    const logoutForm = document.getElementById('logoutForm');
    const quickLogoutForm = document.getElementById('quickActionsLogoutForm');

    if (shutdownButton && logoutForm) {
        shutdownButton.addEventListener('click', function() {
              handleLogout(new Event('click'));
        });
    }
    if (quickLogoutForm) {
        quickLogoutForm.addEventListener('submit', handleLogout);
    }
});

function toggleMenu(event) {
    event.stopPropagation(); // Prevent event bubbling

    const menuIcon = event.currentTarget;
    const verticalMenu = menuIcon.nextElementSibling;

    // Close any other open menus first
    document.querySelectorAll('.vertical-menu').forEach(menu => {
        if (menu !== verticalMenu) {
            menu.style.display = 'none';
        }
    });

    // Toggle the clicked menu
    if (verticalMenu.style.display === 'block') {
        verticalMenu.style.display = 'none';
    } else {
        verticalMenu.style.display = 'block';
    }
}

// Close the menu when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.matches('.menu-icon')) {
        document.querySelectorAll('.vertical-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});

// Update the DOMContentLoaded event listener section
document.addEventListener('DOMContentLoaded', function() {
    // ... existing code ...

    // Setup delete confirmation modal buttons
    document.getElementById('cancelDelete').addEventListener('click', () => {
        hideDeleteConfirmation();
    });

    // Replace the existing confirmDelete click handler with this new one
    document.getElementById('confirmDelete').addEventListener('click', handleDelete);

    // ... rest of your initialization code ...
});

// Update the image handling functions
async function generateImage(prompt) {
    try {
        console.log('Generating image with prompt:', prompt);
        console.log('Conversation URL:', conversationUrl);

        const response = await fetch('/api/chatbot/generate-image', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ prompt })
        });

        console.log('Image generation response status:', response.status);
        console.log('Response headers:', response.headers);

        const data = await response.json();
        console.log('Raw response:', JSON.stringify(data));

        if (data.success && data.url) {
            console.log('Image generation success:', data);
            // Create image element with the direct URL from OpenAI
            const imgElement = document.createElement('img');
            imgElement.src = data.url;
            imgElement.alt = 'Generated image';
            imgElement.style.maxWidth = '100%';
            imgElement.style.borderRadius = '8px';
            imgElement.style.marginTop = '10px';
            
            // Create a container for the image
            const imageContainer = document.createElement('div');
            imageContainer.className = 'generated-image-container';
            imageContainer.appendChild(imgElement);
            
            return imageContainer;
        } else {
            throw new Error(data.error || 'Failed to generate image');
        }
    } catch (error) {
        console.error('Image generation error:', error);
        throw new Error(`Failed to generate image (Status: ${error.message})`);
    }
}

// Update the message display function to handle images
function displayMessage(message, isUser = false) {
    const messageElement = document.createElement('div');
    messageElement.className = isUser ? 'message user-message' : 'message bot-message';
    
    const contentElement = document.createElement('div');
    contentElement.className = 'message-content';
    
    // Check if the message contains an image generation command
    if (!isUser && message.toLowerCase().includes('generating image')) {
        contentElement.textContent = 'Generating image...';
        const loadingSpinner = document.createElement('div');
        loadingSpinner.className = 'loading-spinner';
        contentElement.appendChild(loadingSpinner);
    } else {
        contentElement.textContent = message;
    }
    
    messageElement.appendChild(contentElement);
    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
    
    return messageElement;
}

// Add styles for the image container and loading spinner
const styles = document.createElement('style');
styles.textContent = `
    .generated-image-container {
        margin: 10px 0;
        max-width: 100%;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .generated-image-container img {
        display: block;
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    .generated-image-container img:hover {
        transform: scale(1.02);
    }
    .loading-spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        margin-left: 10px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #dc3545;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
`;
document.head.appendChild(styles);
</script>

<!-- Custom Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Delete chat?</h3>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this chat?</p>
        </div>
        <div class="modal-buttons">
            <button id="cancelDelete" class="cancel-button">Cancel</button>
            <button id="confirmDelete" class="confirm-delete-button">Delete</button>
        </div>
    </div>
</div>

</body>




</html>
