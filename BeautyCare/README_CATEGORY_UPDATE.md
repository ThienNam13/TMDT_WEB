# BeautyCare - Category Update & Enhancement

## Overview
This document describes the category update process and the enhanced features added to the BeautyCare e-commerce website.

## SQL Updates Applied
The following SQL updates were applied to categorize products properly:

```sql
-- Category 1: Dưỡng da (Skincare)
UPDATE san_pham SET ma_danh_muc = 1 WHERE id IN (1, 2, 3, 4, 6, 7, 9, 11, 12, 15, 16, 17, 19, 21, 22, 27, 29, 30, 35);

-- Category 2: Trang điểm (Makeup)
UPDATE san_pham SET ma_danh_muc = 2 WHERE id IN (8, 10, 14, 18, 20, 26, 28);

-- Category 3: Chăm sóc tóc (Hair Care)
UPDATE san_pham SET ma_danh_muc = 3 WHERE id IN (23, 25, 31, 32, 33, 34);

-- Category 4: Mặt nạ (Face Masks)
UPDATE san_pham SET ma_danh_muc = 4 WHERE id IN (5, 13, 24);
```

## Files Updated

### 1. `update_categories.php` (NEW)
- Executes the SQL updates automatically
- Shows success/error status for each update
- Displays current category distribution
- **Usage**: Run this file in your browser to apply the category updates

### 2. `products.php` (ENHANCED)
- Added category navigation bar at the top
- Enhanced filtering system with brand selection
- Improved product display with category labels
- Better responsive design
- Product count display per category

### 3. `index.php` (ENHANCED)
- Category cards with icons and descriptions
- Featured products organized by category
- "View all" links for each category
- Improved styling and hover effects

## How to Apply Updates

### Option 1: Automatic Update (Recommended)
1. Navigate to `BeautyCare/update_categories.php` in your browser
2. The script will automatically execute all SQL updates
3. Check the success messages and category distribution

### Option 2: Manual Database Update
1. Open your database management tool (phpMyAdmin, MySQL Workbench, etc.)
2. Connect to the `myphamdb` database
3. Execute the SQL statements above manually

## Category Structure

| Category ID | Category Name | Description | Product Count |
|-------------|---------------|-------------|---------------|
| 1 | Dưỡng da | Skincare products | 19 products |
| 2 | Trang điểm | Makeup products | 7 products |
| 3 | Chăm sóc tóc | Hair care products | 6 products |
| 4 | Mặt nạ | Face masks | 3 products |

## New Features

### Category Navigation
- Easy switching between product categories
- Visual category indicators
- Active state highlighting

### Enhanced Filtering
- Brand filtering within categories
- Dynamic brand list based on selected category
- Improved filter sidebar design

### Product Display
- Category labels on product cards
- Better product grid layout
- Responsive design for mobile devices
- Hover effects and animations

### Homepage Improvements
- Category-based featured products
- Visual category cards with icons
- Better product organization

## Database Schema
The updates maintain the existing database structure:
- `san_pham` table: Products with `ma_danh_muc` (category ID)
- `danh_muc` table: Categories with `ma_danh_muc` and `ten_danh_muc`

## Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- Responsive design for mobile devices
- CSS Grid and Flexbox for layout

## Notes
- All products now have proper category assignments
- The filtering system works based on these categories
- Product images should be placed in `assets/img/products/`
- Make sure the database connection is properly configured in `php/database.php`

## Troubleshooting
If you encounter issues:
1. Check database connection in `php/database.php`
2. Verify that all product images exist in the correct folder
3. Ensure the `danh_muc` table has the correct category data
4. Check browser console for any JavaScript errors

