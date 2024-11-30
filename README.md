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
- **CSS3 & Bootstrap**: Responsive and styled UI.  
- **JavaScript**: Dynamic functionality.  

### **Backend**
- **PHP**: Server-side scripting and logic.  
- **MySQL**: Database for storing users, businesses, reviews, and ratings.  

### **Additional Tools**
- **Cloudinary** (optional): Media management for business images.  
- **Stripe** (optional): Payment processing for premium features.  

---

## **Database Design**

The database consists of four key tables:
1. **Users Table**: Manages user information and authentication.
2. **Businesses Table**: Stores details about businesses listed on the platform.
3. **Reviews Table**: Tracks user reviews for businesses.
4. **Ratings Table**: Stores star ratings provided by users.

Refer to the `/docs/database_schema.sql` file for the schema.

---

## **Installation**

Follow these steps to set up the project locally:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/yourusername/comment-review-website.git
   cd comment-review-website
   ```

2. **Set Up the Database**:
   - Import the provided `review_website_db.sql` file into your MySQL server.  
   - Update the database credentials in the `config.php` file.

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
- Register and log in to your account.  
- Search for businesses, add reviews, and rate them.  
- Explore top-rated and most-reviewed businesses.  

### For Admins:
- Log in using admin credentials.  
- Add or update business details, reviews, and categories via the dashboard.  

---

## **Screenshots**

### Homepage  
![Homepage Screenshot](https://via.placeholder.com/800x400.png?text=Homepage)

### Business Listing  
![Business Listing Screenshot](https://via.placeholder.com/800x400.png?text=Business+Listing)

### Admin Dashboard  
![Admin Dashboard Screenshot](https://via.placeholder.com/800x400.png?text=Admin+Dashboard)


---

## **Future Enhancements**
 
- **Analytics Dashboard**:  
  Provide insights for business performance.  
- **Voice Search**:  
  Enhance accessibility for users.  
- **Business Ownership Management**:  
  Allow businesses to claim ownership of their listings.  

---

## **Contributing**

Contributions are welcome!  
To contribute:  
1. Fork the repository.  
2. Create a feature branch (`git checkout -b feature-name`).  
3. Commit your changes (`git commit -m 'Add new feature'`).  
4. Push to the branch (`git push origin feature-name`).  
5. Open a pull request.  

---

## **License**

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Feel free to customize the placeholders and adjust the content as per your project needs.
