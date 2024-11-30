# **Comment - A Business Review and Rating Platform**

**Comment** is a professional and user-friendly review website where users can discover, rate, and review businesses. It empowers customers to share their experiences while helping businesses showcase their ratings and reviews. With features like advanced search, category-based filtering, and an admin dashboard, **Comment** provides a seamless experience for users and administrators alike.

---

## **Table of Contents**

1. [Features](#features)  
2. [Technologies Used](#technologies-used)  
3. [Database Design](#database-design)  
4. [Installation](#installation)  
5. [Usage](#usage)  
6. [Screenshots](#screenshots)  
7. [Future Enhancements](#future-enhancements)  
8. [Contributing](#contributing)  
9. [License](#license)

---

## **Features**

- **User Management**:  
  Secure login and registration system with session management.  

- **Business Listings**:  
  Add, view, and manage businesses categorized for easy access.  

- **Review and Rating System**:  
  Post detailed reviews, give star ratings, and view reviews by others.  

- **Search Functionality**:  
  Advanced search with autocomplete, filtering by categories, ratings, or names.  

- **Top Rated and Most Reviewed Sections**:  
  Discover the best-rated businesses and most popular listings.  

- **Admin Dashboard**:  
  Manage businesses, reviews, and categories with intuitive admin controls.  

- **Responsive Design**:  
  Optimized for mobile, tablet, and desktop.  

---

## **Technologies Used**

### **Frontend**
- **HTML5**: Structured layout.  
- **CSS3**: Styled and responsive UI.  
- **JavaScript**: Dynamic functionality.  

### **Backend**
- **PHP**: Server-side scripting and logic.  
- **MySQL**: Database for storing users, businesses, reviews, and ratings.  

---

## **Database Design**

The database consists of four key tables:
1. **Users Table**: Stores user credentials, names, and roles.  
2. **Businesses Table**: Contains details about businesses listed on the platform.  
3. **Reviews Table**: Stores user reviews for businesses.  
4. **Ratings Table**: Tracks star ratings provided by users for businesses.  

Refer to the `/sql/review_website_db.sql` file for the full schema.

---

## **Installation**

Follow these steps to set up the project locally:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/comment-review-website.git
   cd comment-review-website
   ```

2. **Set Up the Database**:
   - Import the `sql/review_website_db.sql` file into your MySQL server.  
   - Update the database connection details in the `includes/config.php` file.

3. **Configure the Project**:
   - Update the `config.php` file with your database connection settings.

4. **Start a Local Server**:
   - Use XAMPP, WAMP, or any PHP local server.
   - Place the project files in the `htdocs` directory if using XAMPP.

5. **Access the Website**:
   Open your browser and navigate to:  
   `http://localhost/comment-review-website/`

---

## **Usage**

### For Users:
- **Register and Login**: Create an account or log in with existing credentials.  
- **Search Businesses**: Use the search bar with autocomplete and filters.  
- **Post Reviews**: Share feedback on businesses with a star rating and review.  
- **Explore**: Browse businesses by categories or find top-rated and most-reviewed listings.  

### For Admins:
- **Dashboard**: Access the admin panel to manage users, businesses, and reviews.  
- **Add/Edit Businesses**: Keep the business database up to date.  
- **Moderate Reviews**: Approve or remove inappropriate reviews.  

---

## **Screenshots**

### Homepage  
![Homepage Screenshot](https://github.com/ALLWIN02/Comment---Review-Website/blob/main/uploads1/h1.jpg?raw=true?text=Homepage)
![Homepage Screenshot](https://github.com/ALLWIN02/Comment---Review-Website/blob/main/uploads1/h1.jpg?raw=true?text=Homepage)
### Business Listing  
![Business Listing Screenshot](https://via.placeholder.com/800x400.png?text=Business+Listing)

### Admin Dashboard  
![Admin Dashboard Screenshot](https://via.placeholder.com/800x400.png?text=Admin+Dashboard)

---

## **Future Enhancements**

- **Analytics Dashboard**:  
  Provide insights on business performance, review trends, and user engagement.  

- **Business Ownership Management**:  
  Enable businesses to claim and manage their listings.  

- **Voice Search**:  
  Add voice-based search functionality for better accessibility.  

- **Premium Features**:  
  Introduce paid plans for businesses to promote their listings.  

---

## **Contributing**

Contributions are welcome!  
To contribute:  

1. Fork the repository.  
2. Create a new branch for your feature:  
   ```bash
   git checkout -b feature-name
   ```  
3. Make your changes and commit them:  
   ```bash
   git commit -m "Add feature description"
   ```  
4. Push to the branch:  
   ```bash
   git push origin feature-name
   ```  
5. Open a pull request on GitHub.

---

## **License**

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Let me know if you need additional sections or further modifications!
