# APTools - Professional Tools E-commerce Platform

A complete frontend user interface built with **Deya PHP Framework** that replicates a professional e-commerce website for tools and equipment.

## Features

### ✨ Complete User Interface
- **Homepage** with auto-rotating banner slider, categories, featured products, testimonials, and newsletter
- **Product Listing** with filters, sorting, and pagination
- **Product Detail** pages with image gallery, tabs, and related products
- **Authentication** pages (Login & Register) with social login options
- **Contact & Support** pages with forms, FAQs, and Google Maps integration
- **Responsive Design** - fully mobile-friendly with hamburger menu

### 🎨 Design Elements
- Modern, clean APTools-inspired design
- Smooth animations and transitions
- Hover effects on cards and buttons
- Interactive product gallery
- Testimonials slider
- Mobile-first responsive layout

### 🛠️ Technical Stack
- **Backend**: Custom Deya PHP Framework
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Icons**: Font Awesome 6.4
- **Fonts**: Google Fonts (Poppins)
- **Architecture**: MVC Pattern

## Installation

### Prerequisites
- PHP 7.4 or higher
- Apache server with mod_rewrite enabled
- XAMPP, WAMP, or similar local server environment

### Setup Instructions

1. **Place the project in your web server directory**
   ```
   c:\xampp\htdocs\host\mod\
   ```

2. **Ensure Apache mod_rewrite is enabled**
   - Check `httpd.conf` for: `LoadModule rewrite_module modules/mod_rewrite.so`
   - Verify `.htaccess` files are being read

3. **Access the application**
   ```
   http://localhost/host/mod/
   ```

## Project Structure

```
mod/
├── app/
│   ├── config/
│   │   └── config.php          # Application configuration
│   ├── controllers/
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── AuthController.php
│   │   ├── ContactController.php
│   │   └── PageController.php
│   ├── core/
│   │   ├── Router.php          # Routing system
│   │   ├── View.php            # View rendering
│   │   └── Request.php         # HTTP request handler
│   ├── routes/
│   │   └── web.php             # Route definitions
│   └── views/
│       ├── layouts/
│       │   └── master.php      # Master layout
│       ├── components/
│       │   ├── header.php
│       │   └── footer.php
│       ├── pages/
│       │   ├── home.php
│       │   ├── products/
│       │   ├── auth/
│       │   ├── contact.php
│       │   └── support.php
│       └── errors/
│           └── 404.php
├── public/
│   └── assets/
│       ├── css/
│       │   ├── variables.css   # CSS variables
│       │   ├── main.css        # Main styles
│       │   ├── products.css    # Product page styles
│       │   ├── contact.css     # Contact page styles
│       │   └── responsive.css  # Responsive breakpoints
│       ├── js/
│       │   └── main.js         # JavaScript interactions
│       └── images/             # Placeholder images
├── index.php                    # Entry point
├── .htaccess                    # URL rewriting
└── README.md
```

## Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/products` | Product listing page |
| `/products/{slug}` | Product detail page |
| `/login` | Login page |
| `/register` | Registration page |
| `/contact` | Contact us page |
| `/support` | Support & FAQ page |
| `/about` | About us page |
| `/privacy` | Privacy policy |
| `/terms` | Terms & conditions |

## Key Features Implemented

### 1. **Responsive Header & Navigation**
- Fixed header with sticky positioning
- Mobile hamburger menu
- Language selector (EN/AR)
- User account dropdown
- Shopping cart icon with count

### 2. **Hero Banner Slider**
- 3 professional promotional banners
- Auto-rotating slider (5-second intervals)
- Left/right navigation arrows
- Dot navigation indicators
- Keyboard support (arrow keys)
- Smooth fade transitions
- Responsive banner scaling
- APTools-inspired design with promotional content

### 3. **Product Categories Section**
- Grid layout with category cards
- Icon-based visual representation
- Hover effects with elevation
- Direct links to filtered product pages

### 4. **Product Features**
- Grid/List view toggle
- Category filters (sidebar)
- Price range filter
- Brand filter
- Sorting options
- Pagination
- Product hover effects
- Quick view & wishlist buttons

### 5. **Product Detail Page**
- Product tabs (Description, Specifications, Reviews)
- Quantity selector
- Add to cart functionality
- Related products section

### 6. **Authentication**
- Clean login/register forms
- Password visibility toggle
- Form validation
- Social login buttons (UI only)
- Error/success alerts
- Forgot password functionality
### 7. **Contact & Support**
- Contact form with validation
- Google Maps integration
- Contact information cards
- FAQ accordion
- Support categories

### 8. **Interactive Elements**
- Hero banner slider with auto-rotation
- Testimonials slider with auto-scroll
- Newsletter subscription form
- Back-to-top button (appears on scroll)
- Smooth scroll animations
- Product quantity controls
- FAQ accordion
- Mobile filter sidebar
- Keyboard navigation support

## Customization

### Changing Brand Colors
Edit `public/assets/css/variables.css`:
```css
:root {
    --primary-color: #e74c3c;    /* Main brand color */
    --secondary-color: #2c3e50;  /* Dark color */
    --accent-color: #3498db;     /* Accent color */
}
```

### Adding New Products
Edit `app/controllers/ProductController.php` and update the mock data arrays, or integrate with a database.

### Contact Information
Edit `app/config/config.php` to update contact details, social links, and other settings.

## Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Optimizations

- Lazy loading for images
- Minified CSS (variables-based)
- Efficient JavaScript (no jQuery dependency)
- Optimized SVG placeholders
- CSS-only animations where possible

## Future Enhancements

To make this production-ready, consider:

1. **Database Integration**
   - Replace mock data with MySQL/PostgreSQL
   - Add ORM or query builder

2. **Authentication Backend**
   - Implement actual user registration/login
   - Add session management
   - Password hashing

3. **Shopping Cart**
   - Add cart functionality
   - Checkout process
   - Payment gateway integration

4. **Admin Panel**
   - Product management
   - Order management
   - User management

5. **Email Integration**
   - Contact form emails
   - Order confirmations
   - Newsletter system

6. **Search Functionality**
   - Product search
   - Autocomplete
   - Search filters

7. **Real Images**
   - Replace SVG placeholders with actual product images

## Credits

- **Framework**: Custom Deya PHP Framework
- **Icons**: Font Awesome
- **Fonts**: Google Fonts (Poppins)
- **Design Inspiration**: APTools Website

## License

This is a demonstration project. All rights reserved.

## Support

For questions or issues, please contact the development team.

---

**Version**: 1.0.0  
**Last Updated**: October 2025
