<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Example</title>
    <style>
      body {
        font-family: "Arial", sans-serif;
        background-color: #f4f4f9;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 1100px;
        box-sizing: border-box;
      }

      form {
        width: 80%;
        max-width: 600px;
        background-color: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      }

      h2 {
        color: #333;
        text-align: center;
        margin-bottom: 25px;
      }

      label,
      input,
      select,
      textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 15px;
      }

      .form-group {
        margin-bottom: 30px;
      }

      .form-group label {
        font-weight: bold;
      }

      input[type="text"],
      input[type="email"],
      select,
      textarea {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
      }

      .error {
        color: red;
        font-size: 0.9em;
      }

      input[type="radio"],
      input[type="checkbox"] {
        margin-right: 5px;
      }

      button[type="submit"] {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        transition: background-color 0.3s ease;
      }

      button[type="submit"]:hover {
        background-color: #218838;
      }

      .form-group label.error {
        color: red;
        margin-top: -15px;
      }
    </style>
  </head>
  <body>
    <h2>User Information Form</h2>

    <form id="userForm" onsubmit="return validateForm(event)">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required />
        <span class="error" id="nameError"></span>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />
        <span class="error" id="emailError"></span>
      </div>

      <div class="form-group">
        <label>Gender:</label><br />
        <input type="radio" id="male" name="gender" value="male" required />
        <label for="male">Male</label>

        <input type="radio" id="female" name="gender" value="female" />
        <label for="female">Female</label>

        <input type="radio" id="other" name="gender" value="other" />
        <label for="other">Other</label>
      </div>

      <div class="form-group">
        <label>Interests:</label><br />
        <input
          type="checkbox"
          id="interest1"
          name="interests[]"
          value="coding"
        />
        <label for="interest1">Coding</label>

        <input
          type="checkbox"
          id="interest2"
          name="interests[]"
          value="travel"
        />
        <label for="interest2">Travel</label>

        <input
          type="checkbox"
          id="interest3"
          name="interests[]"
          value="reading"
        />
        <label for="interest3">Reading</label>
      </div>

      <div class="form-group">
        <label>Age Range:</label><br />
        <select id="ageRange" name="ageRange" required>
          <option value="">Select an age range</option>
          <option value="18-25">18-25</option>
          <option value="26-35">26-35</option>
          <option value="36-45">36-45</option>
          <option value="46-55">46-55</option>
          <option value="56+">56+</option>
        </select>
      </div>

      <div class="form-group">
        <label>Bio:</label><br />
        <textarea id="bio" name="bio"></textarea>
      </div>

      <button type="submit" value="Submit">Submit</button>
    </form>

    <script>
      function validateForm(event) {
        event.preventDefault();

        // Clear previous error messages
        const nameError = document.getElementById("nameError");
        const emailError = document.getElementById("emailError");

        if (document.getElementById("name").value === "") {
          nameError.textContent = "Name is required";
          return false;
        } else {
          nameError.textContent = "";
        }

        if (document.getElementById("email").value === "") {
          emailError.textContent = "Email is required";
          return false;
        } else {
          emailError.textContent = "";
        }

        // If validation passes, you can submit the form or handle it as needed
        alert("Form submitted successfully!");
      }
    </script>
  </body>
</html>
