<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Attendance - Hillside</title>
  <style>
    body { 
      font-family: Arial, sans-serif; 
      background:rgb(11, 88, 24); 
      text-align: center; 
      padding: 20px;
    }
    .container {
      max-width: 500px;
      margin: 0 auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h1 { color: red; }
    h2 { color: blue; }
    input, select, button { 
      width: 80%; 
      margin: 10px 0; 
      padding: 12px; 
      font-size: 16px; 
      border: 1px solid #ddd;
      border-radius: 4px;
      text-align: center;
    }
    input:invalid, select:invalid {
      border-color:rgb(211, 156, 156);
    }
    input:valid, select:valid {
      border-color: #99ff99;
    }
    button {
      background: #1976d2;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #1565c0;
    }
    button:disabled {
      background: #cccccc;
      cursor: not-allowed;
    }
    .spinner {
      display: none;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
      margin: 0 auto;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    .success-animation {
      animation: success 1s;
    }
    @keyframes success {
      0% { background-color: #99ff99; }
      100% { background-color: white; }
    }
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: -8px;
      margin-bottom: 10px;
    }
    .required::after {
      content: " *";
      color: red;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="logo.jpg" alt="School Logo" width="100">
    <h1>HILLSIDE JUNIOR SCHOOL MPUMUDDE NAKAWUKA</h1>
    <h2>Staff Attendance List</h2>

    <form id="attendanceForm">
      <label class="required">Name</label>
      <input type="text" id="name" name="name" placeholder="Enter Full Name" 
             maxlength="50" pattern="[A-Za-z ]+" required>
      <div id="nameError" class="error-message"></div>

      <label class="required">Contact</label>
      <input type="tel" id="contact" name="contact" placeholder="Contact Number" 
             pattern="[0-9]{10,15}" title="10-15 digit phone number" required>
      <div id="contactError" class="error-message"></div>

      <label class="required">Section</label>
      <select id="section" name="section" required>
        <option value="">-- Select Section --</option>
        <option>Nursery Section</option>
        <option>Lower Primary</option>
        <option>Upper Primary</option>
        <option>Head Teacher</option>
        <option>Bursar</option>
        <option>Ass. Computer Tech</option>
        <option>DOS</option>
        <option>Support Staff</option>
      </select>
      <div id="sectionError" class="error-message"></div>

      <button type="submit" id="submitBtn">
        <span id="btnText">REGISTER NOW</span>
        <span id="spinner" class="spinner"></span>
      </button>
    </form>

    <div id="responseMessage"></div>
  </div>

  <script>
    document.getElementById('attendanceForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      
      // Validate form
      if (!validateForm()) return;
      
      // Show loading spinner
      document.getElementById('btnText').style.display = 'none';
      document.getElementById('spinner').style.display = 'block';
      document.getElementById('submitBtn').disabled = true;
      
      try {
        const formData = new FormData(this);
        const response = await fetch('register.php', {
          method: 'POST',
          body: formData
        });
        
        const data = await response.json();
        
        const responseDiv = document.getElementById('responseMessage');
        responseDiv.textContent = data.message;
        responseDiv.style.color = data.success ? 'green' : 'red';
        
        if (data.success) {
          // Success animation
          document.querySelector('.container').classList.add('success-animation');
          setTimeout(() => {
            document.querySelector('.container').classList.remove('success-animation');
          }, 1000);
          
          // Clear form
          this.reset();
        }
      } catch (error) {
        document.getElementById('responseMessage').textContent = 'Registration failed. Please try again.';
        document.getElementById('responseMessage').style.color = 'red';
      } finally {
        // Hide loading spinner
        document.getElementById('btnText').style.display = 'inline';
        document.getElementById('spinner').style.display = 'none';
        document.getElementById('submitBtn').disabled = false;
      }
    });

    function validateForm() {
      let isValid = true;
      
      // Validate name
      const nameInput = document.getElementById('name');
      if (!nameInput.checkValidity()) {
        document.getElementById('nameError').textContent = 'Please enter a valid name (letters and spaces only)';
        isValid = false;
      } else {
        document.getElementById('nameError').textContent = '';
      }
      
      // Validate contact
      const contactInput = document.getElementById('contact');
      if (!contactInput.checkValidity()) {
        document.getElementById('contactError').textContent = 'Please enter a valid 10-15 digit phone number';
        isValid = false;
      } else {
        document.getElementById('contactError').textContent = '';
      }
      
      // Validate section
      const sectionInput = document.getElementById('section');
      if (!sectionInput.checkValidity()) {
        document.getElementById('sectionError').textContent = 'Please select a section';
        isValid = false;
      } else {
        document.getElementById('sectionError').textContent = '';
      }
      
      return isValid;
    }
  </script>
</body>
</html>