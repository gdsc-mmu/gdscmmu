import React, { useState } from 'react';
import './RegisterPopup.css'; 

const RegisterPopup = () => {
    const [email, setEmail] = useState('');
    const [username, setUsername] = useState('');
    const [password, setPassword] = useState('');
    const [showPopup, setShowPopup] = useState(false);
  
    const handleRegister = () => {
      
      console.log('Registering with:', email, username, password);
    };
  
    return (
      <div>
        <button onClick={() => setShowPopup(true)}>Open Register</button>
  
        {showPopup && (
          <div className="register-popup">
            <h2>Register</h2>
            <button className="google-button">
              <img
                src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png" 
                alt="Google Logo"
              />
              Sign in with Google
            </button>
            <input
              type="email"
              placeholder="Email*"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
            <input
              type="text"
              placeholder="Username*"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
            />
            <input
              type="password"
              placeholder="Password*"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
            <button onClick={handleRegister}>Register</button>
            <p>Already got an account? <a href="#">Log In!</a></p>
            <button onClick={() => setShowPopup(false)}>Close</button>
          </div>
        )}
      </div>
    );
  };
  
  export default RegisterPopup;