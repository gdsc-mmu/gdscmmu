import React, { useState } from 'react';
import './LoginPopup.css'; 

const LoginPopup = () => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [showPopup, setShowPopup] = useState(false);

  const handleLogin = () => {
    // Implement your login logic here
    console.log('Logging in with:', username, password);
  };

  return (
    <div>
      <button onClick={() => setShowPopup(true)}>Open Login</button>

      {showPopup && (
        <div className="login-popup">
          <h2>Login</h2>
          <input
            type="text"
            placeholder="Username"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
          />
          <input
            type="password"
            placeholder="Password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          <button onClick={handleLogin}>Login</button>
          <p className='blue'>Forget your password?</p>
          <p>Haven’t got an account? <span className="blue">Sign up!</span></p>
          <button onClick={() => setShowPopup(false)}>Close</button>
        </div>
      )}
    </div>
  );
};

export default LoginPopup;






