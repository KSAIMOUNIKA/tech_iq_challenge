
# TechIQ Challenge

## Introduction  
The TechIQ Challenge is an interactive web-based quiz platform designed to help students improve their knowledge across multiple subjects through quizzes 
and flashcards. Users can register, take quizzes on various topics, view their scores, and create personal flashcards for effective revision.

## Features  
- User authentication (Login/Signup)  
- Subject-based quizzes with multiple-choice questions  
- Score tracking and history  
- Flashcard creation, editing, and deletion  
- Secure password storage using hashing  
- Responsive and user-friendly interface  

## Technologies Used  
- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** Java (Servlets, JDBC)  
- **Database:** MySQL (phpMyAdmin)  
- **Server:** Apache Tomcat  
- **Version Control:** Git & GitHub  

---

## Setup Instructions  

### **1. Install Required Software**  
Ensure the following software is installed:  
- [XAMPP](https://www.apachefriends.org/download.html) (For MySQL & Apache Server)  
- [Java JDK](https://www.oracle.com/java/technologies/javase-downloads.html)  
- [Apache Tomcat](https://tomcat.apache.org/download-90.cgi)  
- [Git](https://git-scm.com/downloads)  

---

### **2. Clone the Repository**  
Run the following commands in your terminal or command prompt:  
```bash
git clone https://github.com/YOUR_GITHUB_USERNAME/tech_iq_challenge.git
cd tech_iq_challenge
```

---

### **3. Setting Up the Database (MySQL - phpMyAdmin)**  
1. Open **XAMPP Control Panel** and start `Apache` & `MySQL`.  
2. Open your browser and go to:  
   ```
   http://localhost/phpmyadmin/
   ```
3. Click "New" on the left sidebar and create a database named:  
   ```
   quiz_app
   ```
4. Click on the **SQL** tab and execute the following queries:

#### **Create `users` Table**  
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
```

#### **Create `questions` Table**  
```sql
CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option1 VARCHAR(255) NOT NULL,
    option2 VARCHAR(255) NOT NULL,
    option3 VARCHAR(255) NOT NULL,
    option4 VARCHAR(255) NOT NULL,
    correct_option VARCHAR(255) NOT NULL,
    subject VARCHAR(100) NOT NULL
);
```

#### **Create `scores` Table**  
```sql
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    score INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### **Create `flashcards` Table**  
```sql
CREATE TABLE flashcards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### **4. Running the Project**  
1. Open **Apache Tomcat** and deploy the project.  
2. Start the server and navigate to:  
   ```
   http://localhost:8080/quiz_app/
   ```
3. Sign up as a new user and start taking quizzes.  

---

## **Additional Notes**  
- Passwords are securely stored using hashing.  
- If additional subjects need to be added, modify `quiz.php` and update the `questions` table accordingly.  
- Ensure the GitHub repository URL is correctly updated while cloning.  

For any issues, open an issue in the GitHub repository.





